<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* world.twig */
class __TwigTemplate_905c8f65df8f46ef5eacceb9e59c89b97fbe230e3270e5231b44aabe169e529e extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<p>Привет, мир!</p><br>

<b>Роутер прекрасно работает</b><br>

<p>Ваш аргумент: ";
        // line 5
        echo twig_escape_filter($this->env, ($context["arg"] ?? null), "html", null, true);
        echo "</p>";
    }

    public function getTemplateName()
    {
        return "world.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  43 => 5,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "world.twig", "/var/www/html/app/view/world.twig");
    }
}
