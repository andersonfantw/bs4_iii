<?php

/* @CoreHome/_favicon.twig */
class __TwigTemplate_23ad3a081d3aef07ba88cb07b54cee9564fff9ec4c1a28cdd9279acfdce5da8b extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        if ((($this->getContext($context, "isCustomLogo") && array_key_exists("customFavicon", $context)) && $this->getContext($context, "customFavicon"))) {
            // line 2
            echo "    <link rel=\"shortcut icon\" href=\"";
            echo twig_escape_filter($this->env, $this->getContext($context, "customFavicon"), "html", null, true);
            echo "\"/>
";
        } else {
            // line 4
            echo "    <link rel=\"shortcut icon\" href=\"plugins/CoreHome/images/favicon.ico\"/>
";
        }
    }

    public function getTemplateName()
    {
        return "@CoreHome/_favicon.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  27 => 4,  21 => 2,  19 => 1,);
    }
}
