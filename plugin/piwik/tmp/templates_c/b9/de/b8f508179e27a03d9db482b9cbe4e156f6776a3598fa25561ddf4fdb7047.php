<?php

/* @CorePluginsAdmin/macros.twig */
class __TwigTemplate_b9deb8f508179e27a03d9db482b9cbe4e156f6776a3598fa25561ddf4fdb7047 extends Twig_Template
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
        // line 54
        echo "
";
        // line 58
        echo "
";
        // line 65
        echo "
";
        // line 95
        echo "
";
        // line 109
        echo "
";
        // line 128
        echo "
";
    }

    // line 1
    public function gettablePluginUpdates($__pluginsHavingUpdate__ = null, $__nonce__ = null, $__isTheme__ = null)
    {
        $context = $this->env->mergeGlobals(array(
            "pluginsHavingUpdate" => $__pluginsHavingUpdate__,
            "nonce" => $__nonce__,
            "isTheme" => $__isTheme__,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 2
            echo "
    <div class='entityContainer'>
        <table class=\"dataTable entityTable\">
            <thead>
            <tr>
                <th>";
            // line 7
            if ($this->getContext($context, "isTheme")) {
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_Theme")), "html", null, true);
            } else {
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Plugin")), "html", null, true);
            }
            echo "</th>
                <th class=\"num\">";
            // line 8
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_Version")), "html", null, true);
            echo "</th>
                <th>";
            // line 9
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Description")), "html", null, true);
            echo "</th>
                <th class=\"status\">";
            // line 10
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_Status")), "html", null, true);
            echo "</th>
                <th class=\"action-links\">";
            // line 11
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Action")), "html", null, true);
            echo "</th>
            </tr>
            </thead>
            <tbody id=\"plugins\">
            ";
            // line 15
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "pluginsHavingUpdate"));
            foreach ($context['_seq'] as $context["name"] => $context["plugin"]) {
                // line 16
                echo "                <tr ";
                if ((($this->getAttribute($context["plugin"], "isActivated", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute($context["plugin"], "isActivated", array()), false)) : (false))) {
                    echo "class=\"active-plugin\"";
                } else {
                    echo "class=\"inactive-plugin\"";
                }
                echo ">
                    <td class=\"name\">
                        <a href=\"javascript:void(0);\" data-pluginName=\"";
                // line 18
                echo twig_escape_filter($this->env, $this->getAttribute($context["plugin"], "name", array()), "html_attr");
                echo "\">
                            ";
                // line 19
                echo twig_escape_filter($this->env, $this->getAttribute($context["plugin"], "name", array()), "html", null, true);
                echo "
                        </a>
                    </td>
                    <td class=\"vers\">
                        ";
                // line 23
                if ($this->getAttribute($context["plugin"], "repositoryChangelogUrl", array())) {
                    // line 24
                    echo "                            <a href=\"javascript:void(0);\" title=\"";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_Changelog")), "html", null, true);
                    echo "\" data-activePluginTab=\"changelog\" data-pluginName=\"";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["plugin"], "name", array()), "html_attr");
                    echo "\">";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["plugin"], "currentVersion", array()), "html", null, true);
                    echo " => ";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["plugin"], "latestVersion", array()), "html", null, true);
                    echo "</a>
                        ";
                } else {
                    // line 26
                    echo "                            ";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["plugin"], "currentVersion", array()), "html", null, true);
                    echo " => ";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["plugin"], "latestVersion", array()), "html", null, true);
                    echo "
                        ";
                }
                // line 28
                echo "                    </td>
                    <td class=\"desc\">
                        ";
                // line 30
                echo twig_escape_filter($this->env, $this->getAttribute($context["plugin"], "description", array()), "html", null, true);
                echo "
                        ";
                // line 31
                echo $this->getAttribute($this, "missingRequirementsPleaseUpdateNotice", array(0 => $context["plugin"]), "method");
                echo "
                    </td>
                    <td class=\"status\">
                        ";
                // line 34
                if ($this->getAttribute($context["plugin"], "isActivated", array())) {
                    // line 35
                    echo "                            ";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_Active")), "html", null, true);
                    echo "
                        ";
                } else {
                    // line 37
                    echo "                            ";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_Inactive")), "html", null, true);
                    echo "
                        ";
                }
                // line 39
                echo "                    </td>
                    <td class=\"togl action-links\">
                        ";
                // line 41
                if ((0 == twig_length_filter($this->env, $this->getAttribute($context["plugin"], "missingRequirements", array())))) {
                    // line 42
                    echo "                            <a href=\"";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('linkTo')->getCallable(), array(array("action" => "updatePlugin", "pluginName" => $this->getAttribute($context["plugin"], "name", array()), "nonce" => $this->getContext($context, "nonce")))), "html", null, true);
                    echo "\">Update</a>
                        ";
                } else {
                    // line 44
                    echo "                            -
                        ";
                }
                // line 46
                echo "                    </td>
                </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['name'], $context['plugin'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 49
            echo "            </tbody>
        </table>
    </div>

";
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    // line 55
    public function getpluginDeveloper($__owner__ = null)
    {
        $context = $this->env->mergeGlobals(array(
            "owner" => $__owner__,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 56
            echo "    ";
            if (("piwik" == $this->getContext($context, "owner"))) {
                echo "<img title=\"Piwik\" alt=\"Piwik\" style=\"padding-bottom:2px;height:11px;\" src=\"plugins/Morpheus/images/logo-marketplace.png\"/>";
            } else {
                echo twig_escape_filter($this->env, $this->getContext($context, "owner"), "html", null, true);
            }
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    // line 59
    public function getfeaturedIcon($__align__ = "")
    {
        $context = $this->env->mergeGlobals(array(
            "align" => $__align__,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 60
            echo "    <img class=\"featuredIcon\"
         title=\"";
            // line 61
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_FeaturedPlugin")), "html", null, true);
            echo "\"
         src=\"plugins/CorePluginsAdmin/images/rating_important.png\"
         align=\"";
            // line 63
            echo twig_escape_filter($this->env, $this->getContext($context, "align"), "html", null, true);
            echo "\" />
";
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    // line 66
    public function getpluginsFilter($__isTheme__ = null, $__isMarketplaceEnabled__ = null)
    {
        $context = $this->env->mergeGlobals(array(
            "isTheme" => $__isTheme__,
            "isMarketplaceEnabled" => $__isMarketplaceEnabled__,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 67
            echo "
    <p class=\"pluginsFilter entityContainer\">
        <span class=\"origin\">
            <strong>";
            // line 70
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_Origin")), "html", null, true);
            echo "</strong>
            <a data-filter-origin=\"all\" href=\"#\" class=\"active\">";
            // line 71
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_All")), "html", null, true);
            echo "<span class=\"counter\"></span></a> |
            <a data-filter-origin=\"core\" href=\"#\">";
            // line 72
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_OriginCore")), "html", null, true);
            echo "<span class=\"counter\"></span></a> |
            <a data-filter-origin=\"noncore\" href=\"#\">";
            // line 73
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_OriginThirdParty")), "html", null, true);
            echo "<span class=\"counter\"></span></a>
        </span>

        <span class=\"status\">
            <strong>";
            // line 77
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_Status")), "html", null, true);
            echo "</strong>
            <a data-filter-status=\"all\" href=\"#\" class=\"active\">";
            // line 78
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_All")), "html", null, true);
            echo "<span class=\"counter\"></span></a> |
            <a data-filter-status=\"active\" href=\"#\">";
            // line 79
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_Active")), "html", null, true);
            echo "<span class=\"counter\"></span></a> |
            <a data-filter-status=\"inactive\" href=\"#\">";
            // line 80
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_Inactive")), "html", null, true);
            echo "<span class=\"counter\"></span></a>
        </span>

        ";
            // line 83
            if ($this->getContext($context, "isMarketplaceEnabled")) {
                // line 84
                echo "            <span class=\"getNewPlugins\">
                ";
                // line 85
                if ($this->getContext($context, "isTheme")) {
                    // line 86
                    echo "                    <a href=\"";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('linkTo')->getCallable(), array(array("action" => "browseThemes", "sort" => ""))), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_InstallNewThemes")), "html", null, true);
                    echo "</a>
                ";
                } else {
                    // line 88
                    echo "                    <a href=\"";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('linkTo')->getCallable(), array(array("action" => "browsePlugins", "sort" => ""))), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_InstallNewPlugins")), "html", null, true);
                    echo "</a>
                ";
                }
                // line 90
                echo "            </span>
        ";
            }
            // line 92
            echo "    </p>

";
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    // line 96
    public function getmissingRequirementsPleaseUpdateNotice($__plugin__ = null)
    {
        $context = $this->env->mergeGlobals(array(
            "plugin" => $__plugin__,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 97
            echo "    ";
            if (($this->getAttribute($this->getContext($context, "plugin"), "missingRequirements", array()) && (0 < twig_length_filter($this->env, $this->getAttribute($this->getContext($context, "plugin"), "missingRequirements", array()))))) {
                // line 98
                echo "        ";
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "plugin"), "missingRequirements", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["req"]) {
                    // line 99
                    echo "<p class=\"missingRequirementsNotice\">
                ";
                    // line 100
                    $context["requirement"] = twig_capitalize_string_filter($this->env, $this->getAttribute($context["req"], "requirement", array()));
                    // line 101
                    echo "                ";
                    if (("Php" == $this->getContext($context, "requirement"))) {
                        // line 102
                        echo "                    ";
                        $context["requirement"] = "PHP";
                        // line 103
                        echo "                ";
                    }
                    // line 104
                    echo "                ";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_MissingRequirementsNotice", $this->getContext($context, "requirement"), $this->getAttribute($context["req"], "actualVersion", array()), $this->getAttribute($context["req"], "requiredVersion", array()))), "html", null, true);
                    echo "
            </p>";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['req'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 107
                echo "    ";
            }
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    // line 110
    public function getmissingRequirementsInfo($__pluginName__ = null, $__metadata__ = null, $__missingRequirements__ = null, $__marketplacePluginNames__ = null)
    {
        $context = $this->env->mergeGlobals(array(
            "pluginName" => $__pluginName__,
            "metadata" => $__metadata__,
            "missingRequirements" => $__missingRequirements__,
            "marketplacePluginNames" => $__marketplacePluginNames__,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 111
            echo "    ";
            $context["causedBy"] = "";
            // line 112
            echo "    ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "missingRequirements"));
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
            foreach ($context['_seq'] as $context["_key"] => $context["dependency"]) {
                // line 113
                echo "        ";
                $context["causedBy"] = ((($this->getContext($context, "causedBy") . twig_capitalize_string_filter($this->env, $this->getAttribute($context["dependency"], "requirement", array()))) . " ") . $this->getAttribute($context["dependency"], "causedBy", array()));
                // line 114
                echo "        ";
                if ( !$this->getAttribute($context["loop"], "last", array())) {
                    // line 115
                    echo "            ";
                    $context["causedBy"] = ($this->getContext($context, "causedBy") . ", ");
                    // line 116
                    echo "        ";
                }
                // line 117
                echo "    ";
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
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['dependency'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 118
            echo "
    ";
            // line 119
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_PluginRequirement", $this->getContext($context, "pluginName"), $this->getContext($context, "causedBy"))), "html", null, true);
            echo "

    ";
            // line 121
            if ((((array_key_exists("metadata", $context) && $this->getAttribute(            // line 122
$this->getContext($context, "metadata", true), "support", array(), "any", true, true)) && $this->getAttribute($this->getAttribute(            // line 123
$this->getContext($context, "metadata"), "support", array()), "email", array())) && !twig_in_filter(            // line 124
$this->getContext($context, "pluginName"), $this->getContext($context, "marketplacePluginNames")))) {
                // line 125
                echo "        ";
                echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_EmailToEnquireUpdatedVersion", (((("<a href=\"mailto:" . twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, "metadata"), "support", array()), "email", array()), "html_attr")) . "\">") . $this->getAttribute($this->getAttribute($this->getContext($context, "metadata"), "support", array()), "email", array())) . "</a>"), $this->getContext($context, "pluginName")));
                echo "
    ";
            }
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    // line 129
    public function gettablePlugins($__pluginsInfo__ = null, $__pluginNamesHavingSettings__ = null, $__activateNonce__ = null, $__deactivateNonce__ = null, $__uninstallNonce__ = null, $__isTheme__ = null, $__marketplacePluginNames__ = null, $__displayAdminLinks__ = null)
    {
        $context = $this->env->mergeGlobals(array(
            "pluginsInfo" => $__pluginsInfo__,
            "pluginNamesHavingSettings" => $__pluginNamesHavingSettings__,
            "activateNonce" => $__activateNonce__,
            "deactivateNonce" => $__deactivateNonce__,
            "uninstallNonce" => $__uninstallNonce__,
            "isTheme" => $__isTheme__,
            "marketplacePluginNames" => $__marketplacePluginNames__,
            "displayAdminLinks" => $__displayAdminLinks__,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 130
            echo "
<div id=\"confirmUninstallPlugin\" class=\"ui-confirm\">

    <h2 id=\"uninstallPluginConfirm\">";
            // line 133
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_UninstallConfirm")), "html", null, true);
            echo "</h2>
    <input role=\"yes\" type=\"button\" value=\"";
            // line 134
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
            echo "\"/>
    <input role=\"no\" type=\"button\" value=\"";
            // line 135
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
            echo "\"/>

</div>

<div class='entityContainer'>
    <table class=\"dataTable entityTable\">
        <thead>
        <tr>
            <th>";
            // line 143
            if ($this->getContext($context, "isTheme")) {
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_Theme")), "html", null, true);
            } else {
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Plugin")), "html", null, true);
            }
            echo "</th>
            <th>";
            // line 144
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Description")), "html", null, true);
            echo "</th>
            <th class=\"status\">";
            // line 145
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_Status")), "html", null, true);
            echo "</th>
            ";
            // line 146
            if ($this->getContext($context, "displayAdminLinks")) {
                // line 147
                echo "            <th class=\"action-links\">";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Action")), "html", null, true);
                echo "</th>
            ";
            }
            // line 149
            echo "        </tr>
        </thead>
        <tbody id=\"plugins\">
        ";
            // line 152
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "pluginsInfo"));
            foreach ($context['_seq'] as $context["name"] => $context["plugin"]) {
                // line 153
                echo "            ";
                $context["isDefaultTheme"] = ($this->getContext($context, "isTheme") && ($context["name"] == "Morpheus"));
                // line 154
                echo "            ";
                if ((($this->getAttribute($context["plugin"], "alwaysActivated", array(), "any", true, true) &&  !$this->getAttribute($context["plugin"], "alwaysActivated", array())) || $this->getContext($context, "isTheme"))) {
                    // line 155
                    echo "                <tr ";
                    if ($this->getAttribute($context["plugin"], "activated", array())) {
                        echo "class=\"active-plugin\"";
                    } else {
                        echo "class=\"inactive-plugin\"";
                    }
                    echo " data-filter-status=\"";
                    if ($this->getAttribute($context["plugin"], "activated", array())) {
                        echo "active";
                    } else {
                        echo "inactive";
                    }
                    echo "\" data-filter-origin=\"";
                    if ($this->getAttribute($context["plugin"], "isCorePlugin", array())) {
                        echo "core";
                    } else {
                        echo "noncore";
                    }
                    echo "\">
                    <td class=\"name\" style=\"white-space:nowrap;\">
                        <a name=\"";
                    // line 157
                    echo twig_escape_filter($this->env, $context["name"], "html_attr");
                    echo "\"></a>
                        ";
                    // line 158
                    if (( !$this->getAttribute($context["plugin"], "isCorePlugin", array()) && twig_in_filter($context["name"], $this->getContext($context, "marketplacePluginNames")))) {
                        // line 159
                        echo "<a href=\"javascript:void(0);\"
                               data-pluginName=\"";
                        // line 160
                        echo twig_escape_filter($this->env, $context["name"], "html_attr");
                        echo "\"
                               >";
                        // line 161
                        echo twig_escape_filter($this->env, $context["name"], "html", null, true);
                        echo "</a>";
                    } else {
                        // line 163
                        echo "                            ";
                        echo twig_escape_filter($this->env, $context["name"], "html", null, true);
                        echo "
                        ";
                    }
                    // line 165
                    echo "                        <span class=\"plugin-version\" ";
                    if ($this->getAttribute($context["plugin"], "isCorePlugin", array())) {
                        echo "title=\"";
                        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_CorePluginTooltip")), "html", null, true);
                        echo "\"";
                    }
                    echo ">(";
                    if ($this->getAttribute($context["plugin"], "isCorePlugin", array())) {
                        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_OriginCore")), "html", null, true);
                    } else {
                        echo "v";
                        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["plugin"], "info", array()), "version", array()), "html", null, true);
                    }
                    echo ")</span>

                        ";
                    // line 167
                    if (twig_in_filter($context["name"], $this->getContext($context, "pluginNamesHavingSettings"))) {
                        // line 168
                        echo "                            <br /><br />
                            <a href=\"";
                        // line 169
                        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('linkTo')->getCallable(), array(array("module" => "CoreAdminHome", "action" => "adminPluginSettings"))), "html", null, true);
                        echo "#";
                        echo twig_escape_filter($this->env, $context["name"], "html_attr");
                        echo "\" class=\"settingsLink\">";
                        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Settings")), "html", null, true);
                        echo "</a>
                        ";
                    }
                    // line 171
                    echo "                    </td>
                    <td class=\"desc\">
                        <div class=\"plugin-desc-missingrequirements\">
                            ";
                    // line 174
                    if (($this->getAttribute($context["plugin"], "missingRequirements", array(), "any", true, true) && $this->getAttribute($context["plugin"], "missingRequirements", array()))) {
                        // line 175
                        echo "                                ";
                        echo $this->getAttribute($this, "missingRequirementsInfo", array(0 => $context["name"], 1 => $this->getAttribute($context["plugin"], "info", array()), 2 => $this->getAttribute($context["plugin"], "missingRequirements", array()), 3 => $this->getContext($context, "marketplacePluginNames")), "method");
                        echo "
                                <br />
                            ";
                    }
                    // line 178
                    echo "                        </div>
                        <div class=\"plugin-desc-text\">

                            ";
                    // line 181
                    echo nl2br($this->getAttribute($this->getAttribute($context["plugin"], "info", array()), "description", array()));
                    echo "

                            ";
                    // line 183
                    if (( !twig_test_empty((($this->getAttribute($this->getAttribute($context["plugin"], "info", array(), "any", false, true), "homepage", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute($this->getAttribute($context["plugin"], "info", array(), "any", false, true), "homepage", array()))) : (""))) && !twig_in_filter($this->getAttribute($this->getAttribute($context["plugin"], "info", array()), "homepage", array()), array(0 => "http://piwik.org", 1 => "http://www.piwik.org", 2 => "http://piwik.org/", 3 => "http://www.piwik.org/")))) {
                        // line 186
                        echo "                            <span class=\"plugin-homepage\">
                                <a href=\"";
                        // line 187
                        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["plugin"], "info", array()), "homepage", array()), "html", null, true);
                        echo "\">(";
                        echo strtr(call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_PluginHomepage")), array(" " => "&nbsp;"));
                        echo ")</a>
                            </span>
                            ";
                    }
                    // line 190
                    echo "                        </div>
                        ";
                    // line 191
                    if ($this->getAttribute($this->getAttribute($context["plugin"], "info", array(), "any", false, true), "license", array(), "any", true, true)) {
                        // line 192
                        echo "                        <div class=\"plugin-license\">
                            ";
                        // line 193
                        if ($this->getAttribute($this->getAttribute($context["plugin"], "info", array(), "any", false, true), "license_homepage", array(), "any", true, true)) {
                            echo "<a title=\"";
                            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_LicenseHomepage")), "html", null, true);
                            echo "\" rel=\"noreferrer\" target=\"_blank\" href=\"";
                            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["plugin"], "info", array()), "license_homepage", array()), "html", null, true);
                            echo "\">";
                        }
                        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["plugin"], "info", array()), "license", array()), "html", null, true);
                        if ($this->getAttribute($this->getAttribute($context["plugin"], "info", array(), "any", false, true), "license_homepage", array(), "any", true, true)) {
                            echo "</a>";
                        }
                        // line 194
                        echo "                        </div>
                        ";
                    }
                    // line 196
                    echo "                        ";
                    if ($this->getAttribute($this->getAttribute($context["plugin"], "info", array(), "any", false, true), "authors", array(), "any", true, true)) {
                        // line 197
                        echo "                        <div class=\"plugin-author\">
                            <cite>By
                            ";
                        // line 199
                        if ($this->getAttribute($this->getAttribute($context["plugin"], "info", array(), "any", false, true), "authors", array(), "any", true, true)) {
                            // line 200
                            ob_start();
                            // line 201
                            echo "                            ";
                            $context['_parent'] = (array) $context;
                            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($context["plugin"], "info", array()), "authors", array()));
                            $context['loop'] = array(
                              'parent' => $context['_parent'],
                              'index0' => 0,
                              'index'  => 1,
                              'first'  => true,
                            );
                            foreach ($context['_seq'] as $context["_key"] => $context["author"]) {
                                if ($this->getAttribute($context["author"], "name", array())) {
                                    // line 202
                                    echo "                                ";
                                    if ($this->getAttribute($context["author"], "homepage", array(), "any", true, true)) {
                                        // line 203
                                        echo "                                    <a title=\"";
                                        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_AuthorHomepage")), "html", null, true);
                                        echo "\" href=\"";
                                        echo twig_escape_filter($this->env, $this->getAttribute($context["author"], "homepage", array()), "html", null, true);
                                        echo "\" rel=\"noreferrer\" target=\"_blank\">";
                                        echo twig_escape_filter($this->env, $this->getAttribute($context["author"], "name", array()), "html", null, true);
                                        echo "</a>
                                ";
                                    } else {
                                        // line 205
                                        echo "                                    ";
                                        echo twig_escape_filter($this->env, $this->getAttribute($context["author"], "name", array()), "html", null, true);
                                        echo "
                                ";
                                    }
                                    // line 207
                                    echo "                                ";
                                    if (($this->getAttribute($context["loop"], "index", array()) < twig_length_filter($this->env, $this->getAttribute($this->getAttribute($context["plugin"], "info", array()), "authors", array())))) {
                                        // line 208
                                        echo "                                        ,
                                    ";
                                    }
                                    // line 210
                                    echo "                            ";
                                    ++$context['loop']['index0'];
                                    ++$context['loop']['index'];
                                    $context['loop']['first'] = false;
                                }
                            }
                            $_parent = $context['_parent'];
                            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['author'], $context['_parent'], $context['loop']);
                            $context = array_intersect_key($context, $_parent) + $_parent;
                            // line 211
                            echo "                            ";
                            echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
                        }
                        // line 212
                        echo ".</cite>
                        </div>
                        ";
                    }
                    // line 215
                    echo "                    </td>
                    <td class=\"status\" ";
                    // line 216
                    if ($this->getContext($context, "isDefaultTheme")) {
                        echo "style=\"border-left-width:0px;\"";
                    }
                    echo ">
                        ";
                    // line 217
                    if ( !$this->getContext($context, "isDefaultTheme")) {
                        // line 219
                        if ($this->getAttribute($context["plugin"], "activated", array())) {
                            // line 220
                            echo "                            ";
                            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_Active")), "html", null, true);
                            echo "
                        ";
                        } else {
                            // line 222
                            echo "                            ";
                            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_Inactive")), "html", null, true);
                            echo "
                            ";
                            // line 223
                            if (($this->getAttribute($context["plugin"], "uninstallable", array()) && $this->getContext($context, "displayAdminLinks"))) {
                                echo " <br/> - <a data-pluginName=\"";
                                echo twig_escape_filter($this->env, $context["name"], "html_attr");
                                echo "\" class=\"uninstall\" href='index.php?module=CorePluginsAdmin&action=uninstall&pluginName=";
                                echo twig_escape_filter($this->env, $context["name"], "html", null, true);
                                echo "&nonce=";
                                echo twig_escape_filter($this->env, $this->getContext($context, "uninstallNonce"), "html", null, true);
                                echo "'>";
                                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_ActionUninstall")), "html", null, true);
                                echo "</a>";
                            }
                            // line 224
                            echo "                        ";
                        }
                    }
                    // line 227
                    echo "                    </td>

                    ";
                    // line 229
                    if ($this->getContext($context, "displayAdminLinks")) {
                        // line 230
                        echo "                    <td class=\"togl action-links\" ";
                        if ($this->getContext($context, "isDefaultTheme")) {
                            echo "style=\"border-left-width:0px;\"";
                        }
                        echo ">
                        ";
                        // line 231
                        if ( !$this->getContext($context, "isDefaultTheme")) {
                            // line 233
                            if (($this->getAttribute($context["plugin"], "invalid", array(), "any", true, true) || $this->getAttribute($context["plugin"], "alwaysActivated", array()))) {
                                // line 234
                                echo "                            -
                        ";
                            } else {
                                // line 236
                                echo "                            ";
                                if ($this->getAttribute($context["plugin"], "activated", array())) {
                                    // line 237
                                    echo "                                <a href='index.php?module=CorePluginsAdmin&action=deactivate&pluginName=";
                                    echo twig_escape_filter($this->env, $context["name"], "html", null, true);
                                    echo "&nonce=";
                                    echo twig_escape_filter($this->env, $this->getContext($context, "deactivateNonce"), "html", null, true);
                                    echo "'>";
                                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_Deactivate")), "html", null, true);
                                    echo "</a>
                            ";
                                } elseif ($this->getAttribute(                                // line 238
$context["plugin"], "missingRequirements", array())) {
                                    // line 239
                                    echo "                                -
                            ";
                                } else {
                                    // line 241
                                    echo "                                <a href='index.php?module=CorePluginsAdmin&action=activate&pluginName=";
                                    echo twig_escape_filter($this->env, $context["name"], "html", null, true);
                                    echo "&nonce=";
                                    echo twig_escape_filter($this->env, $this->getContext($context, "activateNonce"), "html", null, true);
                                    echo "'>";
                                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_Activate")), "html", null, true);
                                    echo "</a>
                            ";
                                }
                                // line 243
                                echo "                        ";
                            }
                        }
                        // line 246
                        echo "                    </td>
                    ";
                    }
                    // line 248
                    echo "                </tr>
            ";
                }
                // line 250
                echo "        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['name'], $context['plugin'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 251
            echo "        </tbody>
    </table>
</div>

";
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    public function getTemplateName()
    {
        return "@CorePluginsAdmin/macros.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  889 => 251,  883 => 250,  879 => 248,  875 => 246,  871 => 243,  861 => 241,  857 => 239,  855 => 238,  846 => 237,  843 => 236,  839 => 234,  837 => 233,  835 => 231,  828 => 230,  826 => 229,  822 => 227,  818 => 224,  806 => 223,  801 => 222,  795 => 220,  793 => 219,  791 => 217,  785 => 216,  782 => 215,  777 => 212,  773 => 211,  763 => 210,  759 => 208,  756 => 207,  750 => 205,  740 => 203,  737 => 202,  725 => 201,  723 => 200,  721 => 199,  717 => 197,  714 => 196,  710 => 194,  698 => 193,  695 => 192,  693 => 191,  690 => 190,  682 => 187,  679 => 186,  677 => 183,  672 => 181,  667 => 178,  660 => 175,  658 => 174,  653 => 171,  644 => 169,  641 => 168,  639 => 167,  622 => 165,  616 => 163,  612 => 161,  608 => 160,  605 => 159,  603 => 158,  599 => 157,  577 => 155,  574 => 154,  571 => 153,  567 => 152,  562 => 149,  556 => 147,  554 => 146,  550 => 145,  546 => 144,  538 => 143,  527 => 135,  523 => 134,  519 => 133,  514 => 130,  496 => 129,  481 => 125,  479 => 124,  478 => 123,  477 => 122,  476 => 121,  471 => 119,  468 => 118,  454 => 117,  451 => 116,  448 => 115,  445 => 114,  442 => 113,  424 => 112,  421 => 111,  407 => 110,  395 => 107,  386 => 104,  383 => 103,  380 => 102,  377 => 101,  375 => 100,  372 => 99,  367 => 98,  364 => 97,  353 => 96,  340 => 92,  336 => 90,  328 => 88,  320 => 86,  318 => 85,  315 => 84,  313 => 83,  307 => 80,  303 => 79,  299 => 78,  295 => 77,  288 => 73,  284 => 72,  280 => 71,  276 => 70,  271 => 67,  259 => 66,  246 => 63,  241 => 61,  238 => 60,  227 => 59,  211 => 56,  200 => 55,  185 => 49,  177 => 46,  173 => 44,  167 => 42,  165 => 41,  161 => 39,  155 => 37,  149 => 35,  147 => 34,  141 => 31,  137 => 30,  133 => 28,  125 => 26,  113 => 24,  111 => 23,  104 => 19,  100 => 18,  90 => 16,  86 => 15,  79 => 11,  75 => 10,  71 => 9,  67 => 8,  59 => 7,  52 => 2,  39 => 1,  34 => 128,  31 => 109,  28 => 95,  25 => 65,  22 => 58,  19 => 54,);
    }
}
