<?php

namespace Phaldan\AssetBuilder;

use Phaldan\AssetBuilder\DependencyInjection\IocContainer;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class ModuleContainer extends IocContainer {

  public function configure() {
    $this->register(Builder\Builder::class, Builder\FluentBuilder::class);
    $this->register(Binder\Binder::class, Binder\SerialBinder::class);
    $this->register(FileSystem\FileSystem::class, FileSystem\FlySystem::class);
    $this->register(Compiler\CssCompiler::class, Compiler\YuiCssCompiler::class);
    $this->register(Compiler\JavaScriptCompiler::class, Compiler\JShrinkCompiler::class);
    $this->register(Compiler\LessCompiler::class, Compiler\OyejorgeLessCompiler::class);
    $this->register(Compiler\ScssCompiler::class, Compiler\LeafoScssCompiler::class);
  }
}