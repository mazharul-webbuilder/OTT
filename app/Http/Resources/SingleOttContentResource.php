<?php

namespace App\Http\Resources;

use App\Models\ContentSource;
use App\Models\OttContent;
use App\Models\OttContentReview;
use App\Models\WishList;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\ContentSourceFormatCheckerTrait;
use Illuminate\Support\Facades\Auth;

class SingleOttContentResource extends JsonResource
{
    use ContentSourceFormatCheckerTrait;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        if ($this->access == config("constants.OTTCONTENTFREE")) {
            return [
                'id' => $this->id,
                'uuid' => $this->uuid,
                'access' => $this->access,
                'title' => $this->title,
                'short_title' => $this->short_title,
                'root_category_id' => $this->root_category_id,
                'sub_category_id' => $this->sub_category_id,
                'sub_sub_category_id' => $this->sub_sub_category_id,
                'series_id' =>  $this->series_id?? null,
                'season_id' =>  $this->season_id?? null,
                'content_type_id' => $this->content_type_id,
                'description' => $this->description,
                'year' => $this->year,
                'runtime' => $this->runtime,
                'youtube_url' => $this->youtube_url,
                'cloud_url' => $this->cloud_url,
                'poster' => $this->poster,
                'backdrop' => $this->backdrop,
                'view_count' => $this->view_count,
                'release_date' => $this->release_date,
                'status' => $this->status,
                'order' => $this->order,
                'content_meta' => OttContentMetaResource::collection($this->ottContentMeta),
                'seasons' => $this->series_id ? new OttSeriesInfoResource($this->ottSeries) : null,
                'season_info' => $this->season_id ? new OttSeasonInfoResource($this->ottSeason) : null,
                'content_source' => ContentSourceResource::collection($this->contentSource),
                'average_review_count' => $this->average_review($this->id),
                'reviews' => OttContentReviewResource::collection($this->reviews),
                'cast_and_crews' => $this->castAndCrew,
                'synopsis_english' => $this->synopsis_english,
                'synopsis_bangla' => $this->synopsis_bangla,
                'thumbnail_portrait' => $this->thumbnail_portrait,
                'thumbnail_landscape' => $this->thumbnail_landscape,
                'imdb' => $this->imdb,
                'saga' => $this->saga,
                'genre' => $this->genre,
                'is_original' => $this->is_original,
                'video_type' => $this->video_type,
                'live_stream_url' => $this->live_stream_url,
                'is_live_stream' => $this->is_live_stream,
                'is_tvod_available' => $this->is_tvod_available,
                't_vod_subscriptions' => $this->is_tvod_available? $this->tVodSubscriptions : [],
                $this->getWishlistMeta(),
            ];
        } else {

            // $availableSources = $this->AvailavleSources($this->id);
            // $content_sources = ContentSource::where('ott_content_id', $this->id)->whereIn('source_format', $availableSources)->get();
            return [
                'id' => $this->id,
                'uuid' => $this->uuid,
                'access' => $this->access,
                'title' => $this->title,
                'short_title' => $this->short_title,
                'root_category_id' => $this->root_category_id,
                'sub_category_id' => $this->sub_category_id,
                'sub_sub_category_id' => $this->sub_sub_category_id,
                'series_id' =>  $this->series_id,
                'content_type_id' => $this->content_type_id,
                'description' => $this->description,
                'year' => $this->year,
                'runtime' => $this->runtime,
                'youtube_url' => $this->youtube_url,
                'cloud_url' => $this->cloud_url,
                'poster' => $this->poster,
                'backdrop' => $this->backdrop,
                'view_count' => $this->view_count,
                'release_date' => $this->release_date,
                'status' => $this->status,
                'order' => $this->order,
                'content_meta' => OttContentMetaResource::collection($this->ottContentMeta),
                'series_info' => $this->series_id ? new OttSeriesInfoResource($this->ottSeries) : null,
                'content_source' => ContentSourceResource::collection($this->contentSource),
                'average_review_count' => $this->average_review($this->id),
                'reviews' => OttContentReviewResource::collection($this->reviews),
                'cast_and_crews' => $this->castAndCrew,
                'synopsis_english' => $this->synopsis_english,
                'synopsis_bangla' => $this->synopsis_bangla,
                'thumbnail_portrait' => $this->thumbnail_portrait,
                'thumbnail_landscape' => $this->thumbnail_landscape,
                'imdb' => $this->imdb,
                'saga' => $this->saga,
                'is_original' => $this->is_original,
                'video_type' => $this->video_type,
                'live_stream_url' => $this->live_stream_url,
                'is_live_stream' => $this->is_live_stream,
                'is_tvod_available' => $this->is_tvod_available,
                't_vod_subscriptions' => $this->is_tvod_available? $this->tVodSubscriptions : null,
                $this->getWishlistMeta(),
            ];
        }
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

    private function getWishlistMeta(){

        $userId = Auth::guard('api')->id();

        $wishListId = WishList::where([
            'user_id' => $userId,
            'content_id' => $this->id
        ])->value('id');

        return $this->mergeWhen((bool) $userId, [
            'wishlist_meta' => [
                'is_in_user_wishlist' => (bool) $wishListId,
                'wishlist_id' => $wishListId
            ]
        ]);
    }
}
