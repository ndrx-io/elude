<?php namespace App\Libraries\Assets;

/**
 * Class Collection
 *
 * @author  LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * @package App\Libraries\Assets
 */
class Collection
{

    private $types = [Asset::CSS, Asset::JS, Asset::SASS, Asset::LESS];

    protected $config;

    protected $assets;

    protected $outputDirectory;

    protected $bowerDirectory;

    protected $tmpDirectory;

    protected $collectionId;

    protected $groupName;

    /**
     * Collection constructor.
     *
     * @param $assets
     *          [
     *          'css' => [
     *          '/path/file.css',
     *          '/path/file2.css'
     *          ],
     *          'js' => [
     *          '/path/file.js',
     *          '/path/file2.js'
     *          ],
     *          'sass' => [
     *          '/path/file.scss',
     *          '/path/file2.scss'
     *          ] ...
     *          ]
     */
    public function __construct ($assets, $groupName = false)
    {
        $this->config          = $assets;
        $this->outputDirectory = config('assets.outputDirectory');
        $this->bowerDirectory  = config('assets.bowerDirectory');

        $this->assets = [];

        foreach ($this->types as $type) {
            if (!isset($assets[$type])) {
                continue;
            }

            foreach ($assets[$type] as $path) {
                $path =  base_path($path);
                if(strpos($path, '*')) {
                    foreach (glob($path) as $filename) {
                        if(is_file($filename)) {
                            $this->assets[$type][] = new Asset($type, $filename);
                        }
                    }
                } else {
                    $this->assets[$type][] = new Asset($type, $path);
                }
            }
        }

        $this->groupName = $groupName;
    }

    public static function createByGroup ($groupName)
    {
        $group = config('assets.groups.' . $groupName, FALSE);
        if ($group === FALSE) {
            throw new \RuntimeException('No assets group named ' . $groupName);
        }

        return new self($group, $groupName);
    }


    /**
     * @param $type
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return array
     */
    public function getType ($type)
    {
        if (isset($this->assets[$type]) && is_array($this->assets[$type])) {
            return $this->assets[$type];
        }

        return [];
    }

    /**
     * @param $type
     * @param $data
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     */
    public function setType ($type, $data)
    {
        $this->assets[$type] = $data;
    }

    /**
     * @param $type
     * @param Asset $asset
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     */
    public function prependType ($type, Asset $asset)
    {
        array_unshift($this->assets[$type], $asset);
    }

    /**
     * @param $type
     * @param Asset $asset
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     */
    public function appendType ($type, Asset $asset)
    {
        $this->assets[$type][] = $asset;
    }

    /**
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     */
    public function initializeFolder ()
    {
        foreach ([$this->outputDirectory, $this->tmpDirectory] as $path) {
            if (!is_dir($path)) {
                if (!mkdir($path, 0777, TRUE)) {
                    throw new \RuntimeException('Can not create folder ' . $path);
                }
            }
        }
    }

    /**
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return string
     */
    public function versionFilePath ()
    {
        return storage_path($this->getCollectionId() . '.json');
    }

    /**
     * @param $build
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return int
     */
    public function writeVersion ($build, $isConcat)
    {

        if (!is_array($build)) {
            $build = [$build];
        }

        return file_put_contents($this->versionFilePath(), json_encode([
            'buildId'      => $this->getCollectionId(),
            'build'        => $build,
            'initialFiles' => $this->config,
            'concat'       => $isConcat,
            'time'         => time()
        ]));
    }

    /**
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return string
     */
    public function getCollectionId ()
    {
        if (is_null($this->collectionId)) {
            $this->collectionId = md5(json_encode($this->config));
        }

        return $this->collectionId;
    }

    /**
     * @return mixed
     */
    public function getConfig ()
    {
        return $this->config;
    }

    /**
     * @param mixed $config
     */
    public function setConfig ($config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getAssets ()
    {
        return $this->assets;
    }

    /**
     * @param array $assets
     */
    public function setAssets ($assets)
    {
        $this->assets = $assets;
    }

    /**
     * @return string
     */
    public function getOutputDirectory ()
    {
        return $this->outputDirectory;
    }

    /**
     * @param string $outputDirectory
     */
    public function setOutputDirectory ($outputDirectory)
    {
        $this->outputDirectory = $outputDirectory;
    }

    /**
     * @return string
     */
    public function getTmpDirectory ()
    {
        return $this->tmpDirectory;
    }

    /**
     * @param string $tmpDirectory
     */
    public function setTmpDirectory ($tmpDirectory)
    {
        $this->tmpDirectory = $tmpDirectory;
    }

    /**
     * @return string
     */
    public function getBowerDirectory ()
    {
        return $this->bowerDirectory;
    }

    /**
     * @param string $bowerDirectory
     */
    public function setBowerDirectory ($bowerDirectory)
    {
        $this->bowerDirectory = $bowerDirectory;
    }

    /**
     * @return boolean|string
     */
    public function getGroupName ()
    {
        return $this->groupName;
    }

    /**
     * @param string $groupName
     */
    public function setGroupName ($groupName)
    {
        $this->groupName = $groupName;
    }
}