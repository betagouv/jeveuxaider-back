<?php

namespace App\Jobs;

use App\Models\Structure;
use App\Models\StructureScore;
use App\Models\Temoignage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;

class StructureCalculateScore implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $structure;
    protected $responseRatio;
    protected $responseTime;
    protected $lastParticipationsResponseRatio;
    protected $bonusPoints;
    protected $engagementPoints;
    protected $reactivityPoints;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($structure)
    {
        $this->structure = $structure;
        $this->onQueue('default');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->batch()?->cancelled()) {
            return;
        }
        if (!$this->structure) {
            return;
        }

        $this->setResponseRatio();
        $this->setResponseTime();
        $this->setLastParticipationsResponseRatio();
        $this->setBonusPoints();
        $this->setEngagementPoints();
        $this->setReactivityPoints();

        $structureScore = StructureScore::updateOrCreate(
            ['structure_id' => $this->structure->id],
            [
                'nb_last_participations' => $this->lastParticipationsResponseRatio['total'],
                'nb_last_participations_with_response' => $this->lastParticipationsResponseRatio['with_response'],
                'response_time' => $this->responseTime,
                'response_ratio' => $this->responseRatio,
                'bonus_points' => $this->bonusPoints,
                'reactivity_points' => $this->reactivityPoints,
                'engagement_points' => $this->engagementPoints,
                'total_points' => $this->getTotalPoints(),
            ]
        );

        // ray('StructureCalculateScore');
        // ray($structureScore);
    }

    private function getTotalPoints()
    {
        if ($this->responseTime == null && $this->responseRatio == null) {
            return 50;
        }

        $score = $this->engagementPoints + $this->reactivityPoints + $this->bonusPoints;
        return $score <= 100 ? round($score) : 100;
    }

    private function setEngagementPoints()
    {
        $this->engagementPoints = round($this->responseRatio * 0.3);
    }

    private function setReactivityPoints()
    {
        $this->reactivityPoints = 0;
        if (!$this->responseTime) {
            return;
        }

        $reactivityPoints = round(100 - (100 * ($this->responseTime / (60 * 60 * 24))) / 10);
        $reactivityPoints = $reactivityPoints > 0 ? $reactivityPoints : 0;
        // Pondérer avec le ratio participations avec réponse / totales
        if ($this->lastParticipationsResponseRatio['total'] > 0) {
            $reactivityPoints = $reactivityPoints * ($this->lastParticipationsResponseRatio['with_response'] / $this->lastParticipationsResponseRatio['total']);
        }

        $this->reactivityPoints = round($reactivityPoints * 0.7);
    }

    private function setBonusPoints()
    {
        $this->bonusPoints = 0;
        $avg = Temoignage::ofStructure($this->structure->id)->avg('grade');
        if (!$avg) {
            return;
        }

        $avg = $avg - 2.5;
        if ($avg > 0) {
            $this->bonusPoints = round($avg * 4, 1);
        }
        else {
            $this->bonusPoints = round($avg * (10 / 1.5), 1);
        }
    }

    private function setResponseRatio()
    {
        $this->responseRatio = null;
        $participationsCount = $this->structure->participations->count();
        if ($participationsCount == 0) {
            return;
        }
        $waitingParticipationsCount = $this->structure->participations->whereIn(
            'state', ['En attente de validation', 'En cours de traitement']
        )->count();

        $this->responseRatio = round(($participationsCount - $waitingParticipationsCount) / $participationsCount * 100);
    }

    private function setResponseTime()
    {
        $avgResponseTime = $this->structure->conversations()
            ->where('conversable_type', 'App\Models\Participation')
            ->latest('conversations.created_at')
            ->take(30)
            ->get()
            ->avg('response_time');

        $this->responseTime = $avgResponseTime ? intval($avgResponseTime) : null;
    }

    private function setLastParticipationsResponseRatio()
    {
        $lastConversationsIds = $this->structure->conversations()
            ->where('conversable_type', 'App\Models\Participation')
            ->latest('conversations.created_at')
            ->take(30)
            ->pluck('conversations.id');

        $lastConversationsWithResponses = $this->structure->conversations()
            ->whereIn('conversations.id', $lastConversationsIds)
            ->whereNotNull('response_time')
            ->pluck('conversations.id');

        $this->lastParticipationsResponseRatio = [
            'with_response' => $lastConversationsWithResponses->count(),
            'total' => $lastConversationsIds->count()
        ];
    }
}
