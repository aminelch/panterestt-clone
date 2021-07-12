<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class PluralizeExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    /**
     * on redéfinit la fonction getFunctions() puisqu'on va créer une fonction twig 
     * la fonction twig s'utilise comme une fonction en php 
     * le filtre twig comme le filtre |length 
     * Quand on appel la fonction pluralize on doit exécuter la fonction doSomething(int $count, string $singular, string $plural)
     *
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('pluralize', [$this, 'doSomething']),
        ];
    }

    public function doSomething(int $count, string $singular, ?string $plural = null)
    {

        // si on a passé l'argument $plural on l'utilise sinon on prend $singular et on ajoute 's' 
        // l'argument $plurar est optionnel 
        // $plural = $plural ?? $singular . 's';
        
        // equivalente à la ligne 41 
        $plural??= $singular.'s';
        return $count == 1 ? "$count $singular" : "$count $plural";
    }
}
