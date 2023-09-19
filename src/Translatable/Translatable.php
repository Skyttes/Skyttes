<?php declare(strict_types=1);

namespace Skyttes\Translatable;

use Nette\Forms\Controls\TextInput;
use Nette\Forms\Form;
use Nette\Utils\Html;
use Skyttes\Application\Kernel;
use Skyttes\Utils\Strings;

class Translatable
{
    public static function transformArray(array $array, ?int $truncate = null): Html
    {
        $str = "";

        foreach ($array as $lang => $value) {
            $value = strip_tags($value);
            if (!empty($truncate)) $value = Strings::truncate($value, $truncate);
            $str .= "[$lang] $value<br/>";
        }
        
        return Html::el("div")->setHtml($str);
    }

    public static function addTranslatableContainer(Form $form, string $name, callable $fn = null) {
        $container = $form->addContainer($name);

        $fn = $fn ?? fn (string $lang) => new TextInput($lang);

        foreach (Kernel::getLanguages() as $lang) {
            $container->addComponent($fn($lang), $lang);
        }

        return $container;
    }
}
