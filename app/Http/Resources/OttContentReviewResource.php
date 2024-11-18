<?php

namespace App\Http\Resources;

use App\Models\OttContent;
use App\Models\OttContentReviewReaction;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class OttContentReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'user'=> $this->getUser($this->user_id),
            'review_star'=>$this->review_star,
            'comment'=>$this->comment,
            'is_helpful'=> OttContentReviewReaction::where('ott_content_review_id',$this->id)->where('is_helpful',1)->count(),
            'inappropriate'=> OttContentReviewReaction::where('ott_content_review_id',$this->id)->where('inappropriate',1)->count(),
        ];
    }
    private function getUser($userId){
        $user = User::where('id',$userId)->with('userMeta')->first();
        $userData = [];
        foreach($user->userMeta as $item){
            if($item->key == 'first_name'){
                $userData['first_name'] = $item->value;
            }
            if($item->key == 'last_name'){
                $userData['last_name'] = $item->value;
            }
            if($item->key == 'image'){
                $userData['image'] = $item->value;
            }
            if($item->key == 'country'){
                $userData['country'] = $item->value;
            }
            
        }
        
        return !empty($userData)? $userData : null;
    }
}
