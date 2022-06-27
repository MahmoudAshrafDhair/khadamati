<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\FavoriteResource;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{

    public function addToFavorite(Request $request){
        $data = $request->all();
        $validator = Validator::make($data,[
            'subCategory_id' => 'required|numeric'
        ],[
            'subCategory_id.required' => __('apiValid.subCategory_id_required'),
            'subCategory_id.numeric' => __('apiValid.subCategory_id_numeric')
        ]);

        if ($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }
        $data['user_id'] = Auth::guard('user-api')->id();
        Favorite::query()->create($data);
        return $this->sendResponse([],__('api.favorite_add'));
    }

    public function getFavorites(){
        $favorites = Favorite::query()->where('user_id',Auth::guard('user-api')->id())->paginate($this->perPage);
        return $this->sendResponse([
            'subCategories' => FavoriteResource::collection($favorites),
            'paginate' => paginate($favorites)
        ],"");
    }

    public function deleteFavorites(Request $request){
        $data = $request->all();
        $validator = Validator::make($data,[
            'subCategory_id' => 'required|numeric'
        ],[
            'subCategory_id.required' => __('apiValid.subCategory_id_required'),
            'subCategory_id.numeric' => __('apiValid.subCategory_id_numeric')
        ]);

        if ($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }

        $favorite = Favorite::query()->where('subCategory_id',$request->subCategory_id)->first();
        $favorite->delete();
        return $this->sendResponse([],__('api.favorite_delete'));
    }
}
