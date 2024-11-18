<?php

namespace App\Http\Resources;

use App\Models\OttContentReview;
use Illuminate\Http\Resources\Json\JsonResource;

class OttContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        // dd($this->contentSource);
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'title' => $this->title,
            'short_title' => $this->short_title,
            'access' => $this->access,
            'youtube_url' => $this->youtube_url,
            'cloud_url' => $this->cloud_url,
            'poster' => $this->poster,
            'view_count' => $this->view_count,
            'release_date' => $this->release_date,
            'status' => $this->status,
            'order' => $this->order,
            'runtime' => $this->runtime,
            'content_source' => $this->contentSource()
                ->select('id', 'ott_content_id', 'content_source', 'source_type', 'video_type')
                ->where(function ($q){
                    $q->where('source_type', 'trailer_path')
                    ->where('processing_status', 'encoded');
            })->get(),
            'average_review_count' => $this->average_review($this->id),
            'thumbnail_portrait' => $this->thumbnail_portrait,
            'thumbnail_landscape' => $this->thumbnail_landscape,
            'synopsis_english' => $this->synopsis_english,
            'synopsis_bangla' => $this->synopsis_bangla,
            'is_tvod_available' => $this->is_tvod_available,
            't_vod_subscriptions' => $this->tVodSubscriptions,
        ];
    }
    private function average_review($content_id)
    {
        $ott_content_reviews = OttContentReview::where('content_id', $content_id)->get();

        if (count($ott_content_reviews) == 0) {
            return 0;
        }
        $ott_content_count_review = OttContentReview::where('content_id', $content_id)->sum('review_star');
        return ceil($ott_content_count_review / count($ott_content_reviews));
    }
}
