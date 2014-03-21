<?php

namespace Luknei\Translations;

use Illuminate\Database\QueryException;
use Illuminate\Console\Command;
use App;
use File;

class LoaderCommand extends Command{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'translations:load';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load translations from files to database.';


    /**
     *
     */
    public function fire()
    {
        $test = App::make('translation.loader');
        $langFolders = $test->getHints();
        $langFolders['*'] = app_path('lang');
        $array = $this->filesAutoLoader($langFolders);
        $data = $this->prepare($array);
        $persist = $this->persist($data);

        $this->info($persist . ' new translations inserted.');
    }

    /**
     * @param array $folders
     * @return array
     */
    protected function filesAutoLoader(array $folders)
    {
        $folders = $this->dirsAutoLoader($folders);

        $list = array();

        foreach ($folders as $namespace => $dirs) {

            foreach ($dirs as $dir) {
                $files = File::files($dir);

                foreach ($files as $file) {
                    $content = File::getRequire($file);

                    if($content === 1) continue;
                    $list[] = array(
                        'namespace' => $namespace,
                        'locale' => $this->getGroup($dir),
                        'group' => $this->getFileName($file),
                        'keys' => $content
                    );
                }
            }
        }

        return $list;
    }

    /**
     * @param array $folders
     * @return array
     */
    protected function dirsAutoLoader(array $folders)
    {
        $list = array();

        foreach ($folders as $hint => $dir) {
            $files = File::directories($dir);

            foreach ($files as $langPath) {

                if (is_dir($langPath)) {
                    $list[$hint][] = $langPath;
                }
            }
        }

        return $list;
    }

    /**
     * @param $string
     * @return mixed
     */
    protected function getGroup($string)
    {
        $string = str_replace('\\', '/', $string);
        $parts = explode('/', $string);

        return array_pop($parts);
    }

    /**
     * @param $string
     * @return mixed
     */
    protected function getFileName($string)
    {
        $file = $this->getGroup($string);
        $name = preg_replace('/\.php$/', '', $file);

        return $name;
    }

    /**
     * @param array $data
     * @return int
     */
    protected function persist(array $data)
    {
        $noRowsInserted = 0;
        foreach ($data as $row) {
            try {
                Translation::create($row);
                $noRowsInserted++;
            } catch (QueryException $e) {

            }
        }
        return $noRowsInserted;
    }

    protected function prepare(array $array)
    {
        $data = array();
        foreach ($array as $file) {
            $keys = array_pop($file);

            foreach ($keys as $key => $val) {
                if (is_array($val)) {
                    $additional = array_dot(array($key => $val));
                    foreach ($additional as $subKey => $subVal) {
                        $file['key'] = $subKey;
                        $file['value'] = $subVal;
                        $data[] = $file;
                    }
                } else {
                    $file['key'] = $key;
                    $file['value'] = $val;
                    $data[] = $file;
                }
            }
        }

        return $data;
    }
} 