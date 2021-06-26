<?php

/* @Insights/table_row.twig */
class __TwigTemplate_f973e78257405082ec4a9207c0bd859c0ddfba29f92da715d8bcd09f1039056e extends Twig_Template
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
        if ($this->getAttribute($this->getContext($context, "row"), "getColumn", array(0 => "isDisappeared"), "method")) {
            // line 2
            echo "    ";
            $context["rowTitle"] = call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_TitleRowDisappearedDetails", $this->getAttribute($this->getContext($context, "row"), "getColumn", array(0 => "label"), "method"), $this->getAttribute($this->getContext($context, "row"), "getColumn", array(0 => "value_old"), "method"), call_user_func_array($this->env->getFilter('prettyDate')->getCallable(), array($this->getAttribute($this->getContext($context, "metadata"), "date", array()), $this->getAttribute($this->getContext($context, "metadata"), "period", array()))), call_user_func_array($this->env->getFilter('prettyDate')->getCallable(), array($this->getAttribute($this->getContext($context, "metadata"), "lastDate", array()), $this->getAttribute($this->getContext($context, "metadata"), "period", array())))));
        } elseif ($this->getAttribute(        // line 3
$this->getContext($context, "row"), "getColumn", array(0 => "isNew"), "method")) {
            // line 4
            echo "    ";
            $context["rowTitle"] = call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_TitleRowNewDetails", $this->getAttribute($this->getContext($context, "row"), "getColumn", array(0 => "label"), "method"), $this->getAttribute($this->getContext($context, "row"), "getColumn", array(0 => "value_new"), "method"), call_user_func_array($this->env->getFilter('prettyDate')->getCallable(), array($this->getAttribute($this->getContext($context, "metadata"), "lastDate", array()), $this->getAttribute($this->getContext($context, "metadata"), "period", array())))));
        } else {
            // line 6
            echo "    ";
            $context["rowTitle"] = call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_TitleRowChangeDetails", $this->getAttribute($this->getContext($context, "row"), "getColumn", array(0 => "label"), "method"), $this->getAttribute($this->getContext($context, "row"), "getColumn", array(0 => "value_old"), "method"), call_user_func_array($this->env->getFilter('prettyDate')->getCallable(), array($this->getAttribute($this->getContext($context, "metadata"), "lastDate", array()), $this->getAttribute($this->getContext($context, "metadata"), "period", array()))), $this->getAttribute($this->getContext($context, "row"), "getColumn", array(0 => "value_new"), "method"), call_user_func_array($this->env->getFilter('prettyDate')->getCallable(), array($this->getAttribute($this->getContext($context, "metadata"), "date", array()), $this->getAttribute($this->getContext($context, "metadata"), "period", array()))), $this->getAttribute($this->getContext($context, "metadata"), "metricName", array())));
        }
        // line 8
        echo "
";
        // line 9
        $context["rowTitleShaker"] = "";
        // line 10
        if ($this->getAttribute($this->getContext($context, "row"), "getColumn", array(0 => "isMoverAndShaker"), "method")) {
            // line 11
            echo "    ";
            $context["rowTitleShaker"] = call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_TitleRowMoverAndShaker"));
        }
        // line 13
        echo "
<tr title=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->getContext($context, "rowTitle"), "html_attr");
        echo " ";
        echo twig_escape_filter($this->env, $this->getContext($context, "rowTitleShaker"), "html_attr");
        echo "\"
    class=\"";
        // line 15
        if ($this->getAttribute($this->getContext($context, "row"), "getColumn", array(0 => "isMoverAndShaker"), "method")) {
            echo "isMoverAndShaker";
        }
        echo "\">
    <td class=\"label\">
        <span class=\"title\">
            ";
        // line 18
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "row"), "getColumn", array(0 => "label"), "method"), "html", null, true);
        echo "
        </span>
    </td>

    ";
        // line 22
        if ($this->getAttribute($this->getContext($context, "row"), "getColumn", array(0 => "grown"), "method")) {
            // line 23
            echo "        <td>+";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "row"), "getColumn", array(0 => "difference"), "method"), "html", null, true);
            echo "</td>
        <td class=\"grown\">+";
            // line 24
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "row"), "getColumn", array(0 => "growth_percent"), "method"), "html", null, true);
            echo "</td>
    ";
        } else {
            // line 26
            echo "        <td>";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "row"), "getColumn", array(0 => "difference"), "method"), "html", null, true);
            echo "</td>
        <td class=\"notGrown\">";
            // line 27
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "row"), "getColumn", array(0 => "growth_percent"), "method"), "html", null, true);
            echo "</td>
    ";
        }
        // line 29
        echo "</tr>";
    }

    public function getTemplateName()
    {
        return "@Insights/table_row.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  91 => 29,  86 => 27,  81 => 26,  76 => 24,  71 => 23,  69 => 22,  62 => 18,  54 => 15,  48 => 14,  45 => 13,  41 => 11,  39 => 10,  37 => 9,  34 => 8,  30 => 6,  26 => 4,  24 => 3,  21 => 2,  19 => 1,);
    }
}
