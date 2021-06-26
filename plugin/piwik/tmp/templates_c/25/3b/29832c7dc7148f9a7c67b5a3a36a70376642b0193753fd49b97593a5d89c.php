<?php

/* @CoreHome/_logo.twig */
class __TwigTemplate_253b29832c7dc7148f9a7c67b5a3a36a70376642b0193753fd49b97593a5d89c extends Twig_Template
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
        echo "<span id=\"logo\">
    <a href=\"index.php\" title=\"";
        // line 2
        if ($this->getContext($context, "isCustomLogo")) {
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_PoweredBy")), "html", null, true);
            echo " ";
        }
        echo "Piwik # ";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_OpenSourceWebAnalytics")), "html", null, true);
        echo "\">
    ";
        // line 3
        if ($this->getContext($context, "hasSVGLogo")) {
            // line 4
            echo "        <img src='";
            echo twig_escape_filter($this->env, $this->getContext($context, "logoSVG"), "html", null, true);
            echo "' alt=\"";
            if ($this->getContext($context, "isCustomLogo")) {
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_PoweredBy")), "html", null, true);
                echo " ";
            }
            echo "Piwik\" class=\"ie-hide ";
            if ( !$this->getContext($context, "isCustomLogo")) {
                echo "default-piwik-logo";
            }
            echo "\" />
        <!--[if lt IE 9]>
    ";
        }
        // line 7
        echo "        <img src='";
        echo twig_escape_filter($this->env, $this->getContext($context, "logoHeader"), "html", null, true);
        echo "' alt=\"";
        if ($this->getContext($context, "isCustomLogo")) {
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_PoweredBy")), "html", null, true);
            echo " ";
        }
        echo "Piwik\" />
    ";
        // line 8
        if ($this->getContext($context, "hasSVGLogo")) {
            echo "<![endif]-->";
        }
        // line 9
        echo "</a>
</span>
";
    }

    public function getTemplateName()
    {
        return "@CoreHome/_logo.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  63 => 9,  59 => 8,  49 => 7,  33 => 4,  31 => 3,  22 => 2,  19 => 1,);
    }
}
