<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\NoteCreateRequest;
use App\Models\Note;
use App\Models\Structure;
use App\Models\Mission;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;

class NoteController extends Controller
{

    private $modelClass;
    private $model;

    public function __construct()
	{
		$this->modelClass = null;
		$this->model = null;
	}

    public function all(Request $request)
    {
        return QueryBuilder::for(Note::role($request->header('Context-Role')))->with(['user', 'notable'])
            ->allowedFilters([
                AllowedFilter::callback('search', function (Builder $query, $value) {
                    if (is_array($value)) {
                        $value = implode(',', $value);
                    }
                    $terms = explode(' ', $value);
                    $query
                        ->whereHas('notable', function(Builder $query) use ($value) {
                            $query->where('name', 'ILIKE', '%'.$value.'%');
                        })
                        ->orWhereHas('user.profile', function (Builder $query) use ($terms) {
                            foreach ($terms as $term) {
                                $query->whereRaw("CONCAT(first_name, ' ', last_name, ' ', email) ILIKE ?", ['%' . $term . '%']);
                            }
                        });;
                }),
                AllowedFilter::callback('mine', function (Builder $query, $value) {
                    $query->where('user_id', auth('api')->user()->id);
                }),
                AllowedFilter::callback('type', function (Builder $query, $value) {
                    if($value == 'organisations') {
                        $query->where('notable_type', 'App\Models\Structure');
                    }
                    if($value == 'missions') {
                        $query->where('notable_type', 'App\Models\Mission');
                    }
                }),
            ])
            ->defaultSort('-created_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function show(Note $note)
    {
        return $note;
    }

    public function update(Request $request, Note $note)
    {

        $note->update($request->all());

        activity('note')
            ->event('updated')
            ->performedOn($note->notable)
            ->log("updated");

        return $note;
    }

    public function delete(Request $request, Note $note)
    {
        $note->delete();

        return true;
    }

    public function index(Request $request, $notable_type, $notable_id)
    {
        $this->getModelClass($notable_type, $notable_id);

        return QueryBuilder::for(Note::where('notable_type', $this->modelClass)->where('notable_id', $this->model->id)->with(['user']))
            ->defaultSort('-created_at')
            ->paginate(5);

    }

    public function store(NoteCreateRequest $request, $notable_type, $notable_id)
    {
        $this->getModelClass($notable_type, $notable_id);

        $attributes = $request->validated();
        $user = auth('api')->user();

        if(!$user){
            return abort(402, "Vous devez être authentifié pour poster un commentaire");
        }

        $note = $this->model->createNote([
            'user_id' => $user->id,
            'content' => $attributes['content']
        ]);

        activity('note')
            ->event('created')
            ->performedOn($this->model)
            ->log("created");

        return $note;
    }

    private function getModelClass($notable_type, $notable_id)
    {
        switch($notable_type){
            case 'structures':
                $this->modelClass = \App\Models\Structure::class;
                $this->model = Structure::find($notable_id);
                break;
            case 'missions':
                $this->modelClass = \App\Models\Mission::class;
                $this->model = Mission::find($notable_id);
                break;
            default:
                abort(402, 'Erreur, ce contenu n\'est pas comment !');
        }

        if(!$this->model) {
            return abort(402, "Erreur, ce contenu n\'existe pas !");
        }

    }

}
