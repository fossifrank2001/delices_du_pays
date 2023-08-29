<?php

namespace App\Http\Controllers\Utils;

use App\Http\Requests\AccountRequestForm;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

class SearcheableMethods
{
    private static $pageSize = 8;
    /**
     * Creates a new instance of CompteSearch with search query applied.
     *
     * @param  Request  $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function account(Request $request, $statut)
    {
        // Create a new query instance for the "comptes" table
        $query = User::query();
        $page = $request->input('page');
//        $statut = $request->get('statut');
        if (isset($statut)) {
            $query->where('STA_ID_STATUT', $statut);
        }

        // Load the request parameters and convert to an array
        $filterParams = $request->input('AccountSearch', []);
        $filterParams = is_array($filterParams) ? $filterParams : $filterParams->toArray();

        if (isset($filterParams['STA_ID_STATUT'])) {
            $query->where('STA_ID_STATUT', $filterParams['STA_ID_STATUT']);
        }

        if (isset($filterParams['STA_ID_STATUT'])) {
            $query->where('STA_ID_STATUT', $filterParams['STA_ID_STATUT']);
        }

        if (isset($filterParams['CTE_LOGIN'])) {
            $query->where('CTE_LOGIN', 'LIKE', "%{$filterParams['CTE_LOGIN']}%");
        }

        if (isset($filterParams['CTE_FIRSTNAME'])) {
            // return $filterParams['CTE_NOM'];
            $query->where('CTE_FIRSTNAME', 'LIKE', "%{$filterParams['CTE_FIRSTNAME']}%");
        }

        if (isset($filterParams['CTE_LASTNAME'])) {
            $query->where('CTE_LASTNAME', 'LIKE', "%{$filterParams['CTE_LASTNAME']}%");
        }

        if (isset($filterParams['CTE_EMAIL'])) {
            $query->where('CTE_EMAIL', 'LIKE', "%{$filterParams['CTE_EMAIL']}%");
        }

        if (isset($filterParams['CTE_PHONE'])) {
            $query->where('CTE_PHONE', 'LIKE', "%{$filterParams['CTE_PHONE']}%");
        }

        $pageSize = self::$pageSize;

        // Use the 'with' method to eager load relationships
        $accounts = $query->with(['statut', 'image', 'accesses', 'notifications'])->paginate($pageSize, ['*'], 'page', $page);

        return $accounts;
    }


    /**
     * Creates a new instance of CompteSearch with search query applied.
     *
     * @param  Request  $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function beveurage(Request $request, $statut, $is_alcoholic)
    {
        // Create a new query instance for the "comptes" table
        $query = Article::query();
        $page = $request->input('page');
        if (isset($statut)) {
            $query->where('STA_ID_STATUT', $statut);
        }
        if (isset($is_alcoholic)) {
            // return=
            // Filter articles based on the is_alcoholic condition from Boisson model
            $query->whereHas('beveurage', function ($query) use ($is_alcoholic) {
                $query->where('BEV_IS_ALCOHOLIC', $is_alcoholic);
            });
            // dd($query->paginate(2));
        }
        // Load the request parameters and convert to an array
        $filterParams = $request->input('BeverageSearch', []);
        $filterParams = is_array($filterParams) ? $filterParams : $filterParams->toArray();

        if (isset($filterParams['ART_NAME'])) {
            $query->where('ART_NAME', 'LIKE', "%{$filterParams['ART_NAME']}%");
        }

        if (isset($filterParams['ART_PRICE'])) {
            $query->where('ART_PRICE', 'LIKE', "%{$filterParams['ART_PRICE']}%");
        }

        if (isset($filterParams['ART_NOTE'])) {
            $query->where('ART_NOTE', 'LIKE', "%{$filterParams['ART_NOTE']}%");
        }

        $pageSize = 10;
        $articles = $query->get();

        // Filter out articles where the meal is null
        $articles = $articles->filter(function ($article) {
            return $article->beveurage !== null;
        });
        foreach ($articles as $article){
            $beveurage = $article->beveurage;
            $image = $beveurage->image;
            $comments = $beveurage->comments->load('compte');
        }
            // return $articles;
        // Paginate the filtered articles
        $page = LengthAwarePaginator::resolveCurrentPage('page');
        $perPage = $pageSize;
        $currentPageItems = $articles->slice(($page - 1) * $perPage, $perPage);
        $articles = new LengthAwarePaginator($currentPageItems, $articles->count(), $perPage, $page);

        // Modify the pagination links to include the domain
        $articles->setPath($request->url());

        // Convert the paginated articles to an array with numeric keys
        $data = $articles->toArray();
        $data['data'] = $data['data'] ? array_values($data['data']) : [];

        return $data;
    }

    /**
     * Creates a new instance of CompteSearch with search query applied.
     *
     * @param  Request  $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function meal(Request $request, $statut, $categoryId) {
        // Create a new query instance for the "comptes" table
        $query = Article::query();
        $page = $request->input('page');
        if (isset($statut)) {
            $query->where('STA_ID_STATUT', $statut);
        }
        if (isset($categoryId)) {
            // Filter articles based on the category ID
            $query->whereHas('meal', function ($query) use ($categoryId) {
                $query->whereHas('categories', function ($query) use ($categoryId) {
                    $query->where('categories.CAT_ID_CATEGORY', $categoryId);
                });
            });
        }
        // Load the request parameters and convert to an array
        $filterParams = $request->input('MealSearch', []);
        $filterParams = is_array($filterParams) ? $filterParams : $filterParams->toArray();

        if (isset($filterParams['ART_NAME'])) {
            $query->where('ART_NAME', 'LIKE', "%{$filterParams['ART_NAME']}%");
        }

        if (isset($filterParams['ART_PRICE'])) {
            $query->where('ART_PRICE', 'LIKE', "%{$filterParams['ART_PRICE']}%");
        }

        if (isset($filterParams['ART_NOTE'])) {
            $query->where('ART_NOTE', 'LIKE', "%{$filterParams['ART_NOTE']}%");
        }

        $pageSize = 10;
        $articles = $query->get();

//         Filter out articles where the meal is null
        $articles = $articles->filter(function ($article) {
            return $article->meal !== null;
        });

        foreach ($articles as $article){
            $meal = $article->meal;
            $categories = $meal->categories;
            $images = $meal->images;
            $comments = $meal->comments->load('compte');
        }

        // Paginate the filtered articles
        $page = LengthAwarePaginator::resolveCurrentPage('page');
        $perPage = $pageSize;
        $currentPageItems = $articles->slice(($page - 1) * $perPage, $perPage);
        $paginatedArticles = new LengthAwarePaginator($currentPageItems, $articles->count(), $perPage, $page);

        // Modify the pagination links to include the domain
        $paginatedArticles->setPath($request->url());

        // Eager load the "meals" relationship for the remaining articles
        // $paginatedArticles->load('meal');

        // Convert the paginated articles to an array with numeric keys
        $data = $paginatedArticles->toArray();
        $data['data'] = $data['data'] ? array_values($data['data']) : [];

        return $data;

    }

}
