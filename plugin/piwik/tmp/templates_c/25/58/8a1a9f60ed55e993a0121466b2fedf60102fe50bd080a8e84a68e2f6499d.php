<?php

/* @CoreAdminHome/_menu.twig */
class __TwigTemplate_25588a1a9f60ed55e993a0121466b2fedf60102fe50bd080a8e84a68e2f6499d extends Twig_Template
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
        echo $context["corehome"]->getsidebarMenu($this->getContext($context, "adminMenu"), $this->getContext($context, "currentModule"), $this->getContext($context, "currentAction"));
    }

    public function getTemplateName()
    {
        return "@CoreAdminHome/_menu.twig";
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
