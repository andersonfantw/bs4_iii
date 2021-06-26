<?php

/* @CoreHome/_userMenu.twig */
class __TwigTemplate_57e0be89456e3948d64b98f071ed30a6ac41dc541c129dfa6236d5060df08cba extends Twig_Template
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
        $context["corehome"] = $this->env->loadTemplate("@CoreHome/macros.twig");
        // line 2
        echo "
";
        // line 3
        echo $context["corehome"]->getsidebarMenu($this->getContext($context, "userMenu"), $this->getContext($context, "currentModule"), $this->getContext($context, "currentAction"));
    }

    public function getTemplateName()
    {
        return "@CoreHome/_userMenu.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  24 => 3,  21 => 2,  19 => 1,);
    }
}
