<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\NoteCreateRequest;
use App\Models\Note;
use App\Models\Structure;
use App\Models\Mission;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Controllers\Controller;
use App\Mail\NoteCreated;
use Illuminate\Support\Facades\Mail;

class NoteController extends Controller
{

    public function __construct()
	{
		$this->modelClass = null;
		$this->model = null;
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

        if(!$user->is_admin){
            foreach (['coralie.chauvin@beta.gouv.fr', 'caroline.farhi@beta.gouv.fr'] as $recipient) {
                Mail::to($recipient)->send(new NoteCreated($note));
            }
        }

        activity('note')
            ->event('created')
            ->performedOn($this->model)
            ->log("created");

        return $note;
    }

}
