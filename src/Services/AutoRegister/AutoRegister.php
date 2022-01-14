<?php
namespace Henrotaym\LaravelContainerAutoRegister\Services\AutoRegister;

use ReflectionClass;
use Illuminate\Support\Collection;
use Henrotaym\LaravelHelpers\Facades\Helpers;
use Henrotaym\LaravelContainerAutoRegister\Services\AutoRegister\Exceptions\FolderNotFound;
use Henrotaym\LaravelContainerAutoRegister\Services\AutoRegister\Contracts\AutoRegisterContract;
use Henrotaym\LaravelContainerAutoRegister\Services\AutoRegister\Contracts\ClassToRegisterContract;

/** Registering classes. */
class AutoRegister implements AutoRegisterContract
{
    /**
     * Adding given folder.
     * 
     * @param string $path
     * @param string $namespace
     * @return self
     */
    protected function addFolder(string $path, string $namespace): self
    {
        foreach (scandir($path) as $file):
            $this->addFile($file, $path, $namespace);
        endforeach;

        return $this;
    }

    /**
     * Adding given file.
     * 
     * @param string $file
     * @param string $actual_path
     * @param string $namespace
     * @return void
     */
    protected function addFile(string $file, string $actual_path, string $namespace)
    {
        if ($file === "." || $file === ".."):
            return;
        endif;

        $file_path = "$actual_path/$file";
        $file_namespace = "$namespace\\" . ucfirst($file);

        // Recursively calling itself for subdirectories.
        if (is_dir($file_path)):
            return $this->addFolder($file_path, $file_namespace);
        endif;

        // Removing file extension for classname.
        $class = substr($file_namespace, 0, strrpos($file_namespace, '.'));

        $query = app()->make(ClassToRegisterContract::class)
            ->setNameWithExtension($file)
            ->setClass($class);

        $query->register();
        
        $this->queries->push($query);
    }

    /**
     * Scanning and registering given folder.
     * 
     * @param string $path Path to scan.
     * @param string $namespace Default namespace for path.
     * 
     * @return Collection|null
     */
    public function scan(string $path, string $namespace): ?Collection
    {
        $this->queries = collect();
        if (!file_exists($path)):
            report(FolderNotFound::path($path));
            return null;
        endif;

        $this->addFolder($path, $namespace);

        return $this->queries;
    }
}