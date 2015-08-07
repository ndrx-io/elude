<?php namespace App\Libraries\Assets\Tasks;

use App\Libraries\Assets\Asset;
use App\Libraries\Assets\Collection;
use League\Pipeline\StageInterface;

/**
 * Class Version
 *
 * @author  LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * @package App\Libraries\Assets\Tasks
 */
class Version implements StageInterface
{

    protected $type;

    function __construct ($type)
    {
        $this->type = $type;
    }

    /**
     * @param Collection $collection
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
    public function process ($collection)
    {
        \Log::info('Assets::Version on collection ' . $collection->getCollectionId());

        $outputDirectory = $collection->getOutputDirectory() . DIRECTORY_SEPARATOR . $this->type . DIRECTORY_SEPARATOR;
        if (!is_dir($outputDirectory) && !mkdir($outputDirectory, 0777, TRUE)) {
            throw new \RuntimeException('Fail to create ' . $outputDirectory);
        }

        $newAssetsFiles = [];
        foreach ($collection->getType($this->type) as $asset) {
            copy($asset->getPath(), $outputDirectory . $collection->getCollectionId() . '.' . $this->type);
            $newAssetsFiles[] = new Asset($this->type, $outputDirectory . $collection->getCollectionId() . '.' . $this->type);
        }

        $collection->setType($this->type, $newAssetsFiles);

        $collection->writeVersion($newAssetsFiles, env('ASSETS_CONCAT'));

        return $collection;
    }
}