<?php

/* @CoreAdminHome/pluginSettings.twig */
class __TwigTemplate_1b387d4a3b01e97b8ccd3ecfcb654d3362b5cd8acbdc3967f11529abca9ee23c extends Twig_Template
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
        return $this->env->resolveTemplate(((($this->getContext($context, "mode") == "user")) ? ("user.twig") : ("admin.twig")));
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "
<div id=\"pluginsSettings\">
    ";
        // line 6
        $context["piwik"] = $this->env->loadTemplate("macros.twig");
        // line 7
        echo "    ";
        $context["ajax"] = $this->env->loadTemplate("ajaxMacros.twig");
        // line 8
        echo "
    ";
        // line 9
        if (($this->getContext($context, "mode") == "user")) {
            // line 10
            echo "        <h2 piwik-enriched-headline>";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_PersonalPluginSettings")), "html", null, true);
            echo "</h2>
    ";
        } else {
            // line 12
            echo "        <h2 piwik-enriched-headline>";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_SystemPluginSettings")), "html", null, true);
            echo "</h2>
    ";
        }
        // line 14
        echo "
    <p>
        ";
        // line 16
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_PluginSettingsIntro")), "html", null, true);
        echo "
        ";
        // line 17
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "pluginsSettings"));
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
        foreach ($context['_seq'] as $context["pluginName"] => $context["settings"]) {
            // line 18
            echo "            <a href=\"#";
            echo twig_escape_filter($this->env, $context["pluginName"], "html_attr");
            echo "\">";
            echo twig_escape_filter($this->env, $context["pluginName"], "html", null, true);
            echo "</a>";
            if ( !$this->getAttribute($context["loop"], "last", array())) {
                echo ", ";
            }
            // line 19
            echo "        ";
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
        unset($context['_seq'], $context['_iterated'], $context['pluginName'], $context['settings'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 20
        echo "    </p>

    <input type=\"hidden\" name=\"setpluginsettingsnonce\" value=\"";
        // line 22
        echo twig_escape_filter($this->env, $this->getContext($context, "nonce"), "html", null, true);
        echo "\">

    ";
        // line 24
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "pluginsSettings"));
        foreach ($context['_seq'] as $context["pluginName"] => $context["pluginSettings"]) {
            // line 25
            echo "
        <h2 id=\"";
            // line 26
            echo twig_escape_filter($this->env, $context["pluginName"], "html_attr");
            echo "\">";
            echo twig_escape_filter($this->env, $context["pluginName"], "html", null, true);
            echo "</h2>

        ";
            // line 28
            if ($this->getAttribute($context["pluginSettings"], "introduction", array())) {
                // line 29
                echo "            <p class=\"pluginIntroduction\">
                ";
                // line 30
                echo twig_escape_filter($this->env, $this->getAttribute($context["pluginSettings"], "introduction", array()), "html", null, true);
                echo "
            </p>
        ";
            }
            // line 33
            echo "
        <table class=\"adminTable\" id=\"pluginSettings\" data-pluginname=\"";
            // line 34
            echo twig_escape_filter($this->env, $context["pluginName"], "html_attr");
            echo "\">

        ";
            // line 36
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["pluginSettings"], "settings", array()));
            foreach ($context['_seq'] as $context["name"] => $context["setting"]) {
                // line 37
                echo "            ";
                $context["settingValue"] = $this->getAttribute($context["setting"], "getValue", array());
                // line 38
                echo "
            ";
                // line 39
                if ($this->getAttribute($context["setting"], "introduction", array())) {
                    // line 40
                    echo "                <tr>
                    <td colspan=\"3\">
                        <p class=\"settingIntroduction\">
                            ";
                    // line 43
                    echo twig_escape_filter($this->env, $this->getAttribute($context["setting"], "introduction", array()), "html", null, true);
                    echo "
                        </p>
                    </td>
                </tr>
            ";
                }
                // line 48
                echo "
            <tr>
                <td class=\"columnTitle\">
                    <span class=\"title\">";
                // line 51
                echo twig_escape_filter($this->env, $this->getAttribute($context["setting"], "title", array()), "html", null, true);
                echo "</span>
                    <br />
                    <span class='form-description'>
                        ";
                // line 54
                echo twig_escape_filter($this->env, $this->getAttribute($context["setting"], "description", array()), "html", null, true);
                echo "
                    </span>

                </td>
                <td class=\"columnField\">
                    <fieldset>
                        <label>
                            ";
                // line 61
                if ((($this->getAttribute($context["setting"], "uiControlType", array()) == "select") || ($this->getAttribute($context["setting"], "uiControlType", array()) == "multiselect"))) {
                    // line 62
                    echo "                                <select
                                    ";
                    // line 63
                    $context['_parent'] = (array) $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["setting"], "uiControlAttributes", array()));
                    foreach ($context['_seq'] as $context["attr"] => $context["val"]) {
                        // line 64
                        echo "                                        ";
                        echo twig_escape_filter($this->env, $context["attr"], "html_attr");
                        echo "=\"";
                        echo twig_escape_filter($this->env, $context["val"], "html_attr");
                        echo "\"
                                    ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['attr'], $context['val'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 66
                    echo "                                    name=\"";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["setting"], "getKey", array()), "html_attr");
                    echo "\"
                                    ";
                    // line 67
                    if (($this->getAttribute($context["setting"], "uiControlType", array()) == "multiselect")) {
                        echo "multiple";
                    }
                    echo ">

                                    ";
                    // line 69
                    $context['_parent'] = (array) $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["setting"], "availableValues", array()));
                    foreach ($context['_seq'] as $context["key"] => $context["value"]) {
                        // line 70
                        echo "                                        <option value='";
                        echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                        echo "'
                                                ";
                        // line 71
                        if ((twig_test_iterable($this->getContext($context, "settingValue")) && twig_in_filter($context["key"], $this->getContext($context, "settingValue")))) {
                            // line 72
                            echo "                                                    selected='selected'
                                                ";
                        } elseif ((                        // line 73
$this->getContext($context, "settingValue") == $context["key"])) {
                            // line 74
                            echo "                                                    selected='selected'
                                                ";
                        }
                        // line 75
                        echo ">
                                            ";
                        // line 76
                        echo twig_escape_filter($this->env, $context["value"], "html", null, true);
                        echo "
                                        </option>
                                    ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['key'], $context['value'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 79
                    echo "
                                </select>
                            ";
                } elseif (($this->getAttribute(                // line 81
$context["setting"], "uiControlType", array()) == "textarea")) {
                    // line 82
                    echo "                                <textarea style=\"width: 376px; height: 250px;\"
                                    ";
                    // line 83
                    $context['_parent'] = (array) $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["setting"], "uiControlAttributes", array()));
                    foreach ($context['_seq'] as $context["attr"] => $context["val"]) {
                        // line 84
                        echo "                                        ";
                        echo twig_escape_filter($this->env, $context["attr"], "html_attr");
                        echo "=\"";
                        echo twig_escape_filter($this->env, $context["val"], "html_attr");
                        echo "\"
                                    ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['attr'], $context['val'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 86
                    echo "                                    name=\"";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["setting"], "getKey", array()), "html_attr");
                    echo "\"
                                    >";
                    // line 88
                    echo twig_escape_filter($this->env, $this->getContext($context, "settingValue"), "html", null, true);
                    // line 89
                    echo "</textarea>
                            ";
                } elseif (($this->getAttribute(                // line 90
$context["setting"], "uiControlType", array()) == "radio")) {
                    // line 91
                    echo "
                                ";
                    // line 92
                    $context['_parent'] = (array) $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["setting"], "availableValues", array()));
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
                    foreach ($context['_seq'] as $context["key"] => $context["value"]) {
                        // line 93
                        echo "
                                    <input
                                        id=\"name-value-";
                        // line 95
                        echo twig_escape_filter($this->env, $this->getAttribute($context["loop"], "index", array()), "html", null, true);
                        echo "\"
                                        ";
                        // line 96
                        $context['_parent'] = (array) $context;
                        $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["setting"], "uiControlAttributes", array()));
                        foreach ($context['_seq'] as $context["attr"] => $context["val"]) {
                            // line 97
                            echo "                                            ";
                            echo twig_escape_filter($this->env, $context["attr"], "html_attr");
                            echo "=\"";
                            echo twig_escape_filter($this->env, $context["val"], "html_attr");
                            echo "\"
                                        ";
                        }
                        $_parent = $context['_parent'];
                        unset($context['_seq'], $context['_iterated'], $context['attr'], $context['val'], $context['_parent'], $context['loop']);
                        $context = array_intersect_key($context, $_parent) + $_parent;
                        // line 99
                        echo "                                        ";
                        if (($this->getContext($context, "settingValue") === $context["key"])) {
                            // line 100
                            echo "                                            checked=\"checked\"
                                        ";
                        }
                        // line 102
                        echo "                                        type=\"radio\"
                                        value=\"";
                        // line 103
                        echo twig_escape_filter($this->env, $context["key"], "html_attr");
                        echo "\"
                                        name=\"";
                        // line 104
                        echo twig_escape_filter($this->env, $this->getAttribute($context["setting"], "getKey", array()), "html_attr");
                        echo "\" />

                                    <label for=\"name-value-";
                        // line 106
                        echo twig_escape_filter($this->env, $this->getAttribute($context["loop"], "index", array()), "html", null, true);
                        echo "\">";
                        echo twig_escape_filter($this->env, $context["value"], "html", null, true);
                        echo "</label>

                                    <br />

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
                    unset($context['_seq'], $context['_iterated'], $context['key'], $context['value'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 111
                    echo "
                            ";
                } else {
                    // line 113
                    echo "
                                <input
                                    ";
                    // line 115
                    $context['_parent'] = (array) $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["setting"], "uiControlAttributes", array()));
                    foreach ($context['_seq'] as $context["attr"] => $context["val"]) {
                        // line 116
                        echo "                                        ";
                        echo twig_escape_filter($this->env, $context["attr"], "html_attr");
                        echo "=\"";
                        echo twig_escape_filter($this->env, $context["val"], "html_attr");
                        echo "\"
                                    ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['attr'], $context['val'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 118
                    echo "                                    ";
                    if (($this->getAttribute($context["setting"], "uiControlType", array()) == "checkbox")) {
                        // line 119
                        echo "                                        value=\"1\"
                                    ";
                    }
                    // line 121
                    echo "                                    ";
                    if ((($this->getAttribute($context["setting"], "uiControlType", array()) == "checkbox") && $this->getContext($context, "settingValue"))) {
                        // line 122
                        echo "                                        checked=\"checked\"
                                    ";
                    }
                    // line 124
                    echo "                                    class=\"control_";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["setting"], "uiControlType", array()), "html_attr");
                    echo "\"
                                    type=\"";
                    // line 125
                    echo twig_escape_filter($this->env, $this->getAttribute($context["setting"], "uiControlType", array()), "html_attr");
                    echo "\"
                                    name=\"";
                    // line 126
                    echo twig_escape_filter($this->env, $this->getAttribute($context["setting"], "getKey", array()), "html_attr");
                    echo "\"
                                    value=\"";
                    // line 127
                    echo twig_escape_filter($this->env, $this->getContext($context, "settingValue"), "html_attr");
                    echo "\"
                                >

                            ";
                }
                // line 131
                echo "
                            ";
                // line 132
                if ((($this->getAttribute($context["setting"], "defaultValue", array()) && ($this->getAttribute($context["setting"], "uiControlType", array()) != "checkbox")) && ($this->getAttribute($context["setting"], "uiControlType", array()) != "radio"))) {
                    // line 133
                    echo "                                <br/>
                                <span class='form-description'>
                                    ";
                    // line 135
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Default")), "html", null, true);
                    echo "
                                    ";
                    // line 136
                    if (twig_test_iterable($this->getAttribute($context["setting"], "defaultValue", array()))) {
                        // line 137
                        echo "                                        ";
                        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('truncate')->getCallable(), array(twig_join_filter($this->getAttribute($context["setting"], "defaultValue", array()), ", "), 50)), "html", null, true);
                        echo "
                                    ";
                    } else {
                        // line 139
                        echo "                                        ";
                        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('truncate')->getCallable(), array($this->getAttribute($context["setting"], "defaultValue", array()), 50)), "html", null, true);
                        echo "
                                    ";
                    }
                    // line 141
                    echo "                                </span>
                            ";
                }
                // line 143
                echo "
                        </label>
                    </fieldset>
                </td>
                <td class=\"columnHelp\">
                    ";
                // line 148
                if ($this->getAttribute($context["setting"], "inlineHelp", array())) {
                    // line 149
                    echo "                        <div class=\"ui-widget\">
                            <div class=\"ui-inline-help\">
                                ";
                    // line 151
                    echo twig_escape_filter($this->env, $this->getAttribute($context["setting"], "inlineHelp", array()), "html", null, true);
                    echo "
                            </div>
                        </div>
                    ";
                }
                // line 155
                echo "                </td>
            </tr>

        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['name'], $context['setting'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 159
            echo "
        </table>

    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['pluginName'], $context['pluginSettings'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 163
        echo "
    <hr class=\"submitSeparator\"/>

    ";
        // line 166
        echo $context["ajax"]->geterrorDiv("ajaxErrorPluginSettings");
        echo "
    ";
        // line 167
        echo $context["ajax"]->getloadingDiv("ajaxLoadingPluginSettings");
        echo "

    <input type=\"submit\" value=\"";
        // line 169
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Save")), "html", null, true);
        echo "\" class=\"pluginsSettingsSubmit submit\"/>

</div>
";
    }

    public function getTemplateName()
    {
        return "@CoreAdminHome/pluginSettings.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  515 => 169,  510 => 167,  506 => 166,  501 => 163,  492 => 159,  483 => 155,  476 => 151,  472 => 149,  470 => 148,  463 => 143,  459 => 141,  453 => 139,  447 => 137,  445 => 136,  441 => 135,  437 => 133,  435 => 132,  432 => 131,  425 => 127,  421 => 126,  417 => 125,  412 => 124,  408 => 122,  405 => 121,  401 => 119,  398 => 118,  387 => 116,  383 => 115,  379 => 113,  375 => 111,  354 => 106,  349 => 104,  345 => 103,  342 => 102,  338 => 100,  335 => 99,  324 => 97,  320 => 96,  316 => 95,  312 => 93,  295 => 92,  292 => 91,  290 => 90,  287 => 89,  285 => 88,  280 => 86,  269 => 84,  265 => 83,  262 => 82,  260 => 81,  256 => 79,  247 => 76,  244 => 75,  240 => 74,  238 => 73,  235 => 72,  233 => 71,  228 => 70,  224 => 69,  217 => 67,  212 => 66,  201 => 64,  197 => 63,  194 => 62,  192 => 61,  182 => 54,  176 => 51,  171 => 48,  163 => 43,  158 => 40,  156 => 39,  153 => 38,  150 => 37,  146 => 36,  141 => 34,  138 => 33,  132 => 30,  129 => 29,  127 => 28,  120 => 26,  117 => 25,  113 => 24,  108 => 22,  104 => 20,  90 => 19,  81 => 18,  64 => 17,  60 => 16,  56 => 14,  50 => 12,  44 => 10,  42 => 9,  39 => 8,  36 => 7,  34 => 6,  30 => 4,  27 => 3,  18 => 1,);
    }
}
