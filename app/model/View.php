<?php


namespace Tandava;

/**
 * Class View
 * @package Tandava
 * Отвечает за Twig
 */
class View
{
    private $loader, $twig;
    private $extension = "twig";

    public function __construct()
    {
        $this->loader = new \Twig\Loader\FilesystemLoader(ROOT . '/app/view');
        $this->twig = new \Twig\Environment($this->loader, [
            'cache' => ROOT . '/app/view/cache',
        ]);
    }

    public function File(string $file, array $args = [], string $extension = ""): string
    {
        if (empty($extension)) $extension = $this->extension;

        return $this->twig->render($file . '.' . $extension, $args);
    }

    /**
     * @param string $extension
     * Устанавливает стандартное расширение
     */
    public function SetExtension(string $extension): void
    {
        $this->extension = $extension;
    }
}