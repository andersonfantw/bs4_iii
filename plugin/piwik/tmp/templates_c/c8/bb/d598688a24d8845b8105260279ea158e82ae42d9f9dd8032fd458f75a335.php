<?php

/* @CoreVisualizations/_dataTableViz_htmlTable.twig */
class __TwigTemplate_c8bbd598688a24d8845b8105260279ea158e82ae42d9f9dd8032fd458f75a335 extends Twig_Template
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
        $context["subtablesAreDisabled"] = ((($this->getAttribute($this->getContext($context, "properties", true), "show_goals_columns", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute($this->getContext($context, "properties", true), "show_goals_columns", array()), false)) : (false)) && (($this->getAttribute(        // line 2
$this->getContext($context, "properties", true), "disable_subtable_when_show_goals", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute($this->getContext($context, "properties", true), "disable_subtable_when_show_goals", array()), false)) : (false)));
        // line 3
        $context["showingEmbeddedSubtable"] = ( !twig_test_empty($this->getAttribute($this->getContext($context, "properties"), "show_embedded_subtable", array())) && ((        // line 4
array_key_exists("idSubtable", $context)) ? (_twig_default_filter($this->getContext($context, "idSubtable"), false)) : (false)));
        // line 5
        if (array_key_exists("error", $context)) {
            // line 6
            echo "    ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "error"), "message", array()), "html", null, true);
            echo "
";
        } else {
            // line 8
            if ( !$this->getContext($context, "showingEmbeddedSubtable")) {
                // line 9
                echo "<table cellspacing=\"0\" class=\"dataTable\">
        ";
                // line 10
                $this->env->loadTemplate("@CoreHome/_dataTableHead.twig")->display($context);
                // line 11
                echo "
        <tbody>";
            }
            // line 14
            if (($this->getContext($context, "showingEmbeddedSubtable") && ($this->getAttribute($this->getContext($context, "dataTable"), "getRowsCount", array(), "method") == 0))) {
                // line 15
                echo "            ";
                if ((( !array_key_exists("clientSideParameters", $context) ||  !$this->getAttribute($this->getContext($context, "clientSideParameters", true), "filter_pattern_recursive", array(), "any", true, true)) ||  !$this->getAttribute($this->getContext($context, "clientSideParameters"), "filter_pattern_recursive", array()))) {
                    // line 16
                    echo "                <tr class=\"nodata\">
                    <td colspan=\"";
                    // line 17
                    echo twig_escape_filter($this->env, twig_length_filter($this->env, $this->getAttribute($this->getContext($context, "properties"), "columns_to_display", array())), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreHome_CategoryNoData")), "html", null, true);
                    echo "</td>
                </tr>
            ";
                }
                // line 20
                echo "        ";
            } else {
                // line 21
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "dataTable"), "getRows", array(), "method"));
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
                foreach ($context['_seq'] as $context["rowId"] => $context["row"]) {
                    // line 22
                    $context["rowHasSubtable"] = (( !$this->getContext($context, "subtablesAreDisabled") && $this->getAttribute($context["row"], "getIdSubDataTable", array(), "method")) &&  !(null === $this->getAttribute($this->getContext($context, "properties"), "subtable_controller_action", array())));
                    // line 23
                    $context["isSummaryRow"] = ($context["rowId"] == twig_constant("Piwik\\DataTable::ID_SUMMARY_ROW"));
                    // line 24
                    $context["shouldHighlightRow"] = ($this->getContext($context, "isSummaryRow") && $this->getAttribute($this->getContext($context, "properties"), "highlight_summary_row", array()));
                    // line 27
                    $context["showRow"] = ((($this->getContext($context, "subtablesAreDisabled") ||  !                    // line 28
$this->getContext($context, "rowHasSubtable")) ||  !(($this->getAttribute(                    // line 29
$this->getContext($context, "properties", true), "show_expanded", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute($this->getContext($context, "properties", true), "show_expanded", array()), false)) : (false))) ||  !(($this->getAttribute(                    // line 30
$this->getContext($context, "properties", true), "replace_row_with_subtable", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute($this->getContext($context, "properties", true), "replace_row_with_subtable", array()), false)) : (false)));
                    // line 32
                    if ($this->getContext($context, "showRow")) {
                        // line 33
                        echo "                <tr ";
                        if ($this->getContext($context, "rowHasSubtable")) {
                            echo "id=\"";
                            echo twig_escape_filter($this->env, $this->getAttribute($context["row"], "getIdSubDataTable", array(), "method"), "html", null, true);
                            echo "\"";
                        }
                        // line 34
                        echo "                    ";
                        if ( !call_user_func_array($this->env->getTest('false')->getCallable(), array($this->getAttribute($context["row"], "getMetadata", array(0 => "segment"), "method")))) {
                            echo " data-segment-filter=\"";
                            echo twig_escape_filter($this->env, $this->getAttribute($context["row"], "getMetadata", array(0 => "segment"), "method"), "html_attr");
                            echo "\"";
                        }
                        // line 35
                        echo "                    ";
                        if ( !call_user_func_array($this->env->getTest('false')->getCallable(), array($this->getAttribute($context["row"], "getMetadata", array(0 => "url"), "method")))) {
                            echo " data-url-label=\"";
                            echo call_user_func_array($this->env->getFilter('rawSafeDecoded')->getCallable(), array($this->getAttribute($context["row"], "getMetadata", array(0 => "url"), "method")));
                            echo "\"";
                        }
                        // line 36
                        echo "                    class=\"";
                        echo twig_escape_filter($this->env, $this->getAttribute($context["row"], "getMetadata", array(0 => "css_class"), "method"), "html", null, true);
                        echo " ";
                        if ($this->getContext($context, "rowHasSubtable")) {
                            echo "subDataTable";
                        }
                        if ($this->getContext($context, "shouldHighlightRow")) {
                            echo " highlight";
                        }
                        if ($this->getContext($context, "isSummaryRow")) {
                            echo " summaryRow";
                        }
                        echo "\"
                    ";
                        // line 37
                        if ($this->getContext($context, "rowHasSubtable")) {
                            echo "title=\"";
                            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreHome_ClickRowToExpandOrContract")), "html", null, true);
                            echo "\"";
                        }
                        echo ">
                    ";
                        // line 38
                        $context['_parent'] = (array) $context;
                        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "properties"), "columns_to_display", array()));
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
                        foreach ($context['_seq'] as $context["_key"] => $context["column"]) {
                            // line 39
                            echo "                        <td>
                            ";
                            // line 40
                            $this->env->loadTemplate("@CoreHome/_dataTableCell.twig")->display(array_merge($context, $this->getContext($context, "properties")));
                            // line 41
                            echo "                        </td>
                    ";
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
                        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['column'], $context['_parent'], $context['loop']);
                        $context = array_intersect_key($context, $_parent) + $_parent;
                        // line 43
                        echo "                </tr>
                ";
                    }
                    // line 45
                    echo "
                ";
                    // line 47
                    echo "                ";
                    if (((($this->getAttribute($this->getContext($context, "properties", true), "show_expanded", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute($this->getContext($context, "properties", true), "show_expanded", array()), false)) : (false)) && $this->getContext($context, "rowHasSubtable"))) {
                        // line 48
                        echo "                    ";
                        $this->env->loadTemplate("@CoreVisualizations/_dataTableViz_htmlTable.twig")->display(array_merge($context, array("dataTable" => $this->getAttribute($context["row"], "getSubtable", array(), "method"), "idSubtable" => $this->getAttribute($context["row"], "getIdSubDataTable", array(), "method"))));
                        // line 49
                        echo "                ";
                    }
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
                unset($context['_seq'], $context['_iterated'], $context['rowId'], $context['row'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
            }
            // line 52
            if ( !$this->getContext($context, "showingEmbeddedSubtable")) {
                // line 53
                echo "</tbody>
    </table>";
            }
        }
    }

    public function getTemplateName()
    {
        return "@CoreVisualizations/_dataTableViz_htmlTable.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  205 => 53,  203 => 52,  187 => 49,  184 => 48,  181 => 47,  178 => 45,  174 => 43,  159 => 41,  157 => 40,  154 => 39,  137 => 38,  129 => 37,  114 => 36,  107 => 35,  100 => 34,  93 => 33,  91 => 32,  89 => 30,  88 => 29,  87 => 28,  86 => 27,  84 => 24,  82 => 23,  80 => 22,  63 => 21,  60 => 20,  52 => 17,  49 => 16,  46 => 15,  44 => 14,  40 => 11,  38 => 10,  35 => 9,  33 => 8,  27 => 6,  25 => 5,  23 => 4,  22 => 3,  20 => 2,  19 => 1,);
    }
}
