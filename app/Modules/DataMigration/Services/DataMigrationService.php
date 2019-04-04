<?php
/**
 * Created by PhpStorm.
 * User: rayndeng
 * Date: 29/3/19
 * Time: 9:36 AM
 */

namespace App\Modules\DataMigration\Services;


use App\Modules\Keywords\Models\Page;
use App\Modules\Keywords\Models\QueryDetails;
use App\Modules\Keywords\Models\QueryProfile;

class DataMigrationService
{

    public function generatePathMd5ForGooglePage()
    {
        $data = Page::query()->whereNull('path_md5')->get();
        foreach ($data as $datum) {
            $path = $this->getPathByUrl($datum->url);
            $datum->shortcut_path = substr($path, 0, 254);
            $datum->path_md5 = md5($path);
            $datum->save();
        }
    }

    public function getPathByUrl($url)
    {
        $url = parse_url($url)['path'];
        $path = substr($url, 0, 1) === '/' ? substr($url, 1) : $url;
        return $path === '' ? '/' : $path;
    }


    public function queryRankingMigration()
    {
        $limit = 10000;
        $data = QueryDetails::query()->whereNull('average_weight_ranking')->limit($limit)->get();

        while ($data->count() > 0) {
            foreach ($data as $datum) {
                $datum->average_weight_ranking = round($datum->impressions * $datum->position, 6);
                $datum->save();
            }
            $data = QueryDetails::query()->whereNull('average_weight_ranking')->limit($limit)->get();
        }
    }

    public function migrateQueryProfileKeywordField()
    {
        $data = QueryProfile::query()->whereNotNull('index')->get();

        foreach ($data as $datum) {
            $datum->keyword = explode('_', $datum->index)[1];
            $datum->save();
        }
    }


    public function indexMigration()
    {
        $limit = 10000;
        $data = QueryDetails::query()->whereNull('index')->limit($limit)->get();
        while ($data->count() > 0) {
            foreach ($data as $datum) {
                $datum->index = $datum->page . '_' . $datum->keyword;
                $datum->save();
            }
            $data = QueryDetails::query()->whereNull('index')->limit($limit)->get();
        }
    }

}