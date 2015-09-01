<?php namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;

class Profiler extends Controller
{

    public function index()
    {

        return response()->json($this->getDataFromJson());
    }

    public function last()
    {
        $profils = $this->getDataFromJson();
        $last = null;

        foreach ($profils as $profil) {
            if (is_null($last)) {
                $last = $profil;
            } elseif ($profil['timestamp'] > $last['timestamp']) {
                $last = $profil;
            }
        }

        return response()->json($last);
    }

    public function show($id)
    {
        $filename = storage_path('clockwork/' . $id . '.json');
        if (!is_file($filename)) {
            return response()->json([], 404);
        }

        $profile = json_decode(file_get_contents($filename));

        if (is_bool($profile)) {
            return response()->json([], 503);
        }

        $profile->id = $id;
        $profile->timestamp = Carbon::createFromTimestamp($profile->time)->timestamp;
        $profile->datetime = Carbon::createFromTimestamp($profile->time)->toIso8601String();
        $profile->duration = floor($profile->responseDuration);
        $profile->nbSqlQueries = count($profile->databaseQueries);

        $start = $profile->timelineData->total->start;

        foreach($profile->timelineData as $key => $item) {
            $profile->timelineData->{$key}->start = floor(($item->start - $start) * 1000);
            $profile->timelineData->{$key}->end = floor(($item->end - $start) * 1000);
            $profile->timelineData->{$key}->duration = floor($item->duration);
        }


        return response()->json($profile);
    }

    protected function getDataFromJson()
    {
        $results = [];
        $finder = Finder::create();
        $finder->name('*.json')->date('since 1 day ago');
        /** @var File $file */
        foreach ($finder->in(storage_path('clockwork')) as $file) {
            $tmp = json_decode($file->getContents());
            if (is_bool($tmp)) {
                continue;
            }

            $results[] = [
                'id' => $file->getBasename('.json'),
                'method' => $tmp->method,
                'responseStatus' => $tmp->responseStatus,
                'datetime' => Carbon::createFromTimestamp($tmp->time)->toIso8601String(),
                'timestamp' => Carbon::createFromTimestamp($tmp->time)->timestamp,
                'uri' => $tmp->uri,
                'duration' => floor($tmp->responseDuration),
                'nbSqlQueries' => count($tmp->databaseQueries)
            ];

            unset($tmp);
        }

        return $results;
    }
}