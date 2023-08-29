<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\GlobalMethods;
use App\Http\Requests\CommentFormRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Nullable;

class CommentController extends Controller
{
    public function make(CommentFormRequest $request){
        $validated = $request->validated();
//        dd(Auth::check());
        // Vérifiez si l'utilisateur est authentifié
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Récupérez l'objet commentable en utilisant le type et l'ID fournis dans la requête
        $commentableType = $validated['COMMENTABLE_type'];
        $commentableId = $validated['COMMENTABLE_id'];
        $commentable = \App\Http\Controllers\Utils\GlobalMethods::retrieveModels($commentableType, $commentableId);
//        dd($commentableId);
        // Créez un nouveau commentaire en utilisant les données de la requête et l'utilisateur connecté
        $comment = new Comment([
            'COM_CONTENT' => $validated['COM_CONTENT'],
            'CTE_ID_COMPTE' => Auth::user()->CTE_ID_COMPTE,
            "COM_CREATED_AT" => GlobalMethods::setTimeZone(),
            "COM_UPDATED_AT" => GlobalMethods::setTimeZone()
        ]);

        // Enregistrez le commentaire pour l'objet commentable
        $commentable->comments()->save($comment);

        return response()->json($comment);
    }
    public function update(CommentFormRequest $request, Comment $comment){
        $validated = $request->validated();

        $user = Auth::user();
        $userId = $user->CTE_ID_COMPTE;
        if ($userId  !== $comment->CTE_ID_COMPTE) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->update([
            'COM_CONTENT' => $validated['COM_CONTENT'],
            'COM_UPDATED_AT' => GlobalMethods::setTimeZone()
        ]);

        return response()->json($comment);
    }
    public function destroy(Comment $comment){
        $user = Auth::user();
        $userId = $user->CTE_ID_COMPTE;
        if ($userId  !== $comment->CTE_ID_COMPTE) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(null, 200);
    }
}
