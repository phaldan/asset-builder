<?php

namespace Phaldan\AssetBuilder;

use Phaldan\AssetBuilder\DependencyInjection\IocContainer;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class ModuleContainer extends IocContainer {

  public function configure() {
    $this->register(Builder\Builder::class, Builder\FluentBuilder::class);
    $this->register(Binder\Binder::class, Binder\CachedSerialBinder::class);
    $this->register(Cache\Cache::class, Cache\FileCache::class);
    $this->register(FileSystem\FileSystem::class, FileSystem\FlySystem::class);
    $this->register(Processor\CssProcessor::class, Processor\YuiCssCompiler::class);
    $this->register(Processor\JavaScriptProcessor::class, Processor\JShrinkProcessor::class);
    $this->register(Processor\LessCompiler::class, Processor\OyejorgeLessCompiler::class);
    $this->register(Processor\ScssCompiler::class, Processor\LeafoScssCompiler::class);
  }
}