<?php

namespace App\AdminModule\Presenters;

use Nette;
use App\Model;
use WebLoader;
use Nette\Utils\Finder;
use \CssMin;

class BasePresenter extends Nette\Application\UI\Presenter {

    public function startup() {
        parent::startup();
        define('WWW_DIR', $this->context->parameters['wwwDir']);
    }

    protected function createComponentCss() {

        $files = new WebLoader\FileCollection(WWW_DIR . '/css');

        $files->addFiles(
                Finder::findFiles('*.css', '*.less')->in(WWW_DIR . '/css')
        );

        $files->addWatchFiles(Finder::findFiles('*.css', '*.less')->in(WWW_DIR . '/css'));

        $compiler = WebLoader\Compiler::createCssCompiler($files, WWW_DIR . '/compile/css');
        $compiler->addFilter(function ($code) {
            return CssMin::minify($code);
        });


        $control = new WebLoader\Nette\CssLoader($compiler, $this->template->basePath . '/compile/css');
        $control->setMedia('screen');

        return $control;
    }

}
