<?php

/* @Login/resetPassword.twig */
class __TwigTemplate_243c7ed5d2f736cf1397f7a4c165c1b7373ed3ab754dcd79c3ef16393bb21c3a extends Twig_Template
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
        if ((array_key_exists("infoMessage", $context) &&  !twig_test_empty($this->getContext($context, "infoMessage")))) {
            // line 2
            echo "    <p class=\"message\">";
            echo twig_escape_filter($this->env, $this->getContext($context, "infoMessage"), "html", null, true);
            echo "</p>
";
        }
        // line 4
        $this->env->loadTemplate("@Login/_formErrors.twig")->display($context);
    }

    public function getTemplateName()
    {
        return "@Login/resetPassword.twig";
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
