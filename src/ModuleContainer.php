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
    $this->register(Processor\Minifier\CssProcessor::class, Processor\Minifier\YuiCssProcessor::class);
    $this->register(Processor\Minifier\JavaScriptProcessor::class, Processor\Minifier\JShrinkProcessor::class);
    $this->register(Processor\PreProcessor\LessProcessor::class, Processor\PreProcessor\OyejorgeLessProcessor::class);
    $this->register(Processor\PreProcessor\ScssProcessor::class, Processor\PreProcessor\LeafoScssProcessor::class);
  }
}