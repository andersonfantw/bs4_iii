<?php

/* @Insights/table_header.twig */
class __TwigTemplate_30a9f1995a055615eed0ede162b98ce3c2fc6cd2ac4f96044fe99facd52d9af0 extends Twig_Template
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
        echo "<tr>
    <th class=\"label\">
        ";
        // line 3
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "metadata"), "reportName", array()), "html", null, true);
        echo "
    </th>
    <th class=\"label orderBy ";
        // line 5
        if (("absolute" == $this->getAttribute($this->getContext($context, "properties"), "order_by", array()))) {
            echo "active";
        }
        echo "\"
        name=\"orderBy\" value=\"absolute\">
        ";
        // line 7
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "metadata"), "metricName", array()), "html", null, true);
        echo "
    </th>
    <th class=\"label orderBy ";
        // line 9
        if (("relative" == $this->getAttribute($this->getContext($context, "properties"), "order_by", array()))) {
            echo "active";
        }
        echo "\"
        name=\"orderBy\" value=\"relative\">
        ";
        // line 11
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("MultiSites_Evolution")), "html", null, true);
        echo "
    </th>
</tr>";
    }

    public function getTemplateName()
    {
        return "@Insights/table_header.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  47 => 11,  40 => 9,  35 => 7,  28 => 5,  23 => 3,  19 => 1,);
    }
}
