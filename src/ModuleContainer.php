<?php

namespace Phaldan\AssetBuilder;

use Phaldan\AssetBuilder\DependencyInjection\IocContainer;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class ModuleContainer extends IocContainer {

  public function configure() {
    $this->register(Builder\Builder::class, Builder\FluentBuilder::class);
    $this->register(Binder\Binder::class, Binder\AsyncBinder::class);
  }
}