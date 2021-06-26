<?php

/* @MultiSites/getSitesInfo.twig */
class __TwigTemplate_7b666253920eb94cd4a22ed71d0bc6206aec0979c14996daeefeee0ce3439f57 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return $this->env->resolveTemplate((($this->getContext($context, "isWidgetized")) ? ("empty.twig") : ("dashboard.twig")));
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        if ( !$this->getContext($context, "isWidgetized")) {
            // line 5
            echo "    <div class=\"top_controls\">
        ";
            // line 6
            $this->env->loadTemplate("@CoreHome/_periodSelect.twig")->display($context);
            // line 7
            echo "        ";
            $this->env->loadTemplate("@CoreHome/_headerMessage.twig")->display($context);
            // line 8
            echo "    </div>
";
        }
        // line 10
        echo "
<div class=\"pageWrap container\" id=\"multisites\">
    <div id=\"main\">
        <div piwik-multisites-dashboard
             display-revenue-column=\"";
        // line 14
        if ($this->getContext($context, "displayRevenueColumn")) {
            echo "true";
        } else {
            echo "false";
        }
        echo "\"
             page-size=\"";
        // line 15
        echo twig_escape_filter($this->env, $this->getContext($context, "limit"), "html", null, true);
        echo "\"
             show-sparklines=\"";
        // line 16
        if ($this->getContext($context, "show_sparklines")) {
            echo "true";
        } else {
            echo "false";
        }
        echo "\"
             date-sparkline=\"";
        // line 17
        echo twig_escape_filter($this->env, $this->getContext($context, "dateSparkline"), "html", null, true);
        echo "\"
             auto-refresh-today-report=\"";
        // line 18
        echo twig_escape_filter($this->env, $this->getContext($context, "autoRefreshTodayReport"), "html", null, true);
        echo "\">
        </div>
    </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "@MultiSites/getSitesInfo.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  74 => 18,  70 => 17,  62 => 16,  58 => 15,  50 => 14,  44 => 10,  40 => 8,  37 => 7,  35 => 6,  32 => 5,  30 => 4,  27 => 3,  18 => 1,);
    }
}
