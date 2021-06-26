<?php

/* @Insights/insightVisualization.twig */
class __TwigTemplate_58b3e62958ec0031dfb1ba2d97be16ebe8f1012ad00c2ce59f223debc91c95c0 extends Twig_Template
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
        if ((array_key_exists("cannotDisplayReport", $context) && $this->getContext($context, "cannotDisplayReport"))) {
            // line 2
            echo "    ";
            $this->env->loadTemplate("@Insights/cannotDisplayReport.twig")->display($context);
        } else {
            // line 4
            echo "    ";
            $context["metadata"] = $this->getAttribute($this->getContext($context, "dataTable"), "getAllTableMetadata", array());
            // line 5
            echo "    ";
            $context["consideredGrowth"] = call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_TitleConsideredInsightsGrowth", $this->getAttribute($this->getContext($context, "metadata"), "minGrowthPercentPositive", array()), call_user_func_array($this->env->getFilter('prettyDate')->getCallable(), array($this->getAttribute($this->getContext($context, "metadata"), "lastDate", array()), $this->getAttribute($this->getContext($context, "metadata"), "period", array())))));
            // line 6
            echo "    ";
            $context["consideredChanges"] = "";
            // line 7
            echo "
    ";
            // line 8
            if (($this->getAttribute($this->getContext($context, "metadata"), "minChangeMovers", array()) && ($this->getAttribute($this->getContext($context, "metadata"), "minChangeMovers", array()) > 1))) {
                // line 9
                echo "        ";
                $context["consideredChanges"] = call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_IgnoredChanges", $this->getAttribute($this->getContext($context, "metadata"), "minChangeMovers", array())));
                // line 10
                echo "    ";
            }
            // line 11
            echo "
    <div class=\"insightsDataTable\" title=\"";
            // line 12
            echo twig_escape_filter($this->env, $this->getContext($context, "consideredGrowth"), "html_attr");
            echo " ";
            echo twig_escape_filter($this->env, $this->getContext($context, "consideredChanges"), "html_attr");
            echo "\">
        ";
            // line 13
            if ($this->getAttribute($this->getContext($context, "dataTable"), "getRowsCount", array())) {
                // line 14
                echo "            <table class=\"dataTable\">

                <thead>
                    ";
                // line 17
                $this->env->loadTemplate("@Insights/table_header.twig")->display($context);
                // line 18
                echo "                </thead>

                <tbody>
                    ";
                // line 21
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "dataTable"), "getRows", array()));
                $context['loop'] = array(
                  'parent' => $context['_parent'],
                  'index0' => 0,
                  'index'  => 1,
                  'first'  => true,
                );
                if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
                    $length = count($context['_seq']);
                    $context['loop']['revindex0'] = $length - 1;
                    $context['loop']['revindex'] = $length;
                    $context['loop']['length'] = $length;
                    $context['loop']['last'] = 1 === $length;
                }
                foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
                    // line 22
                    echo "                        ";
                    $this->env->loadTemplate("@Insights/table_row.twig")->display($context);
                    // line 23
                    echo "                    ";
                    ++$context['loop']['index0'];
                    ++$context['loop']['index'];
                    $context['loop']['first'] = false;
                    if (isset($context['loop']['length'])) {
                        --$context['loop']['revindex0'];
                        --$context['loop']['revindex'];
                        $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                    }
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 24
                echo "                </tbody>
            </table>
        ";
            } else {
                // line 27
                echo "            <div class=\"pk-emptyDataTable\">
                ";
                // line 28
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_NoResultMatchesCriteria")), "html", null, true);
                echo "
            </div>
        ";
            }
            // line 31
            echo "
        ";
            // line 32
            $this->env->loadTemplate("@Insights/insightControls.twig")->display($context);
            // line 33
            echo "    </div>
";
        }
    }

    public function getTemplateName()
    {
        return "@Insights/insightVisualization.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  121 => 33,  119 => 32,  116 => 31,  110 => 28,  107 => 27,  102 => 24,  88 => 23,  85 => 22,  68 => 21,  63 => 18,  61 => 17,  56 => 14,  54 => 13,  48 => 12,  45 => 11,  42 => 10,  39 => 9,  37 => 8,  34 => 7,  31 => 6,  28 => 5,  25 => 4,  21 => 2,  19 => 1,);
    }
}
