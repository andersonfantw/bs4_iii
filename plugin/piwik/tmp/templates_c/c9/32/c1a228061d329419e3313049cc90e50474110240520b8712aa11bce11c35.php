<?php

/* @UsersManager/userSettings.twig */
class __TwigTemplate_c932c1a228061d329419e3313049cc90e50474110240520b8712aa11bce11c35 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        try {
            $this->parent = $this->env->loadTemplate("user.twig");
        } catch (Twig_Error_Loader $e) {
            $e->setTemplateFile($this->getTemplateName());
            $e->setTemplateLine(1);

            throw $e;
        }

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "user.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "<h2 piwik-enriched-headline>";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_PersonalSettings")), "html", null, true);
        echo "</h2>

<div class=\"ui-confirm\" id=\"confirmPasswordChange\">
    <h2>";
        // line 7
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_ChangePasswordConfirm")), "html", null, true);
        echo "</h2>
    <input role=\"yes\" type=\"button\" value=\"";
        // line 8
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
        echo "\"/>
    <input role=\"no\" type=\"button\" value=\"";
        // line 9
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
        echo "\"/>
</div>

<table id='userSettingsTable' class=\"adminTable\">
    <tr>
        <td><label for=\"username\">";
        // line 14
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Username")), "html", null, true);
        echo " </label></td>
        <td>
            <input size=\"25\" value=\"";
        // line 16
        echo twig_escape_filter($this->env, $this->getContext($context, "userLogin"), "html", null, true);
        echo "\" id=\"username\" disabled=\"disabled\"/>
            <span class='form-description'>";
        // line 17
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_YourUsernameCannotBeChanged")), "html", null, true);
        echo "</span>
        </td>
    </tr>

    <tr>
        <td><label for=\"alias\">";
        // line 22
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_Alias")), "html", null, true);
        echo " </label></td>
        <td><input size=\"25\" value=\"";
        // line 23
        echo twig_escape_filter($this->env, $this->getContext($context, "userAlias"), "html", null, true);
        echo "\" id=\"alias\" /></td>
    </tr>
    <tr>
        <td><label for=\"email\">";
        // line 26
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_Email")), "html", null, true);
        echo " </label></td>
        <td><input size=\"25\" value=\"";
        // line 27
        echo twig_escape_filter($this->env, $this->getContext($context, "userEmail"), "html", null, true);
        echo "\" id=\"email\"/></td>
    </tr>
    <tr>
        <td><div style=\"margin-top: 5px;margin-bottom: 10px;\">";
        // line 30
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Language")), "html", null, true);
        echo "</div></td>
        <td>
            <fieldset style=\"margin-top: 5px;margin-bottom: 10px;\">
                <select name=\"language\" id=\"language\" onchange=\"if (this.value=='') window.open('?module=Proxy&amp;action=redirect&amp;url=http://piwik.org/translations/');\">
                    <option title=\"\" value=\"\">";
        // line 34
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("LanguagesManager_AboutPiwikTranslations")), "html", null, true);
        echo "</option>
                    ";
        // line 35
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "languages"));
        foreach ($context['_seq'] as $context["_key"] => $context["language"]) {
            // line 36
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["language"], "code", array()), "html", null, true);
            echo "\" ";
            if (($this->getAttribute($context["language"], "code", array()) == $this->getContext($context, "currentLanguageCode"))) {
                echo "selected=\"selected\"";
            }
            // line 37
            echo "                                title=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["language"], "name", array()), "html", null, true);
            echo " (";
            echo twig_escape_filter($this->env, $this->getAttribute($context["language"], "english_name", array()), "html", null, true);
            echo ")\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["language"], "name", array()), "html", null, true);
            echo "</option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['language'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 39
        echo "                </select>
                <br />
            </fieldset>
        </td>
    </tr>
    <tr>
        <td>";
        // line 45
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_ReportToLoadByDefault")), "html", null, true);
        echo "</td>
        <td>
            <fieldset>
                <input id=\"defaultReportRadioAll\" type=\"radio\" value=\"MultiSites\"
                      name=\"defaultReport\"";
        // line 49
        if (($this->getContext($context, "defaultReport") == "MultiSites")) {
            echo " checked=\"checked\"";
        }
        echo " />
                <label for=\"defaultReportRadioAll\">";
        // line 50
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_AllWebsitesDashboard")), "html", null, true);
        echo "</label><br/>
                <input id=\"defaultReportSpecific\" type=\"radio\" value=\"1\"
                       name=\"defaultReport\"";
        // line 52
        if (($this->getContext($context, "defaultReport") != "MultiSites")) {
            echo " checked=\"checked\"";
        }
        echo " />
                <label for=\"defaultReportSpecific\" style=\"padding-right:12px;\">";
        // line 53
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_DashboardForASpecificWebsite")), "html", null, true);
        echo "</label>

                <div piwik-siteselector
                     class=\"sites_autocomplete\"
                     siteid=\"";
        // line 57
        echo twig_escape_filter($this->env, $this->getContext($context, "defaultReportIdSite"), "html", null, true);
        echo "\"
                     sitename=\"";
        // line 58
        echo twig_escape_filter($this->env, $this->getContext($context, "defaultReportSiteName"), "html", null, true);
        echo "\"
                     switch-site-on-select=\"false\"
                     show-all-sites-item=\"false\"
                     showselectedsite=\"true\"
                     id=\"defaultReportSiteSelector\"></div>
            </fieldset>
        </td>
    </tr>
    <tr>
        <td>";
        // line 67
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_ReportDateToLoadByDefault")), "html", null, true);
        echo "</td>
        <td>
            <fieldset>
                ";
        // line 70
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "availableDefaultDates"));
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
        foreach ($context['_seq'] as $context["value"] => $context["description"]) {
            // line 71
            echo "                    <input id=\"defaultDate-";
            echo twig_escape_filter($this->env, $this->getAttribute($context["loop"], "index", array()), "html", null, true);
            echo "\" type=\"radio\"";
            if (($this->getContext($context, "defaultDate") == $context["value"])) {
                echo " checked=\"checked\"";
            }
            echo " value=\"";
            echo twig_escape_filter($this->env, $context["value"], "html", null, true);
            echo "\" name=\"defaultDate\"/>
                    <label for=\"defaultDate-";
            // line 72
            echo twig_escape_filter($this->env, $this->getAttribute($context["loop"], "index", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $context["description"], "html", null, true);
            echo "</label>
                    <br/>
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
        unset($context['_seq'], $context['_iterated'], $context['value'], $context['description'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 75
        echo "            </fieldset>
        </td>
    </tr>

    ";
        // line 79
        if ((array_key_exists("isValidHost", $context) && $this->getContext($context, "isValidHost"))) {
            // line 80
            echo "        <tr>
            <td><label for=\"email\">";
            // line 81
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_ChangePassword")), "html", null, true);
            echo " </label></td>
            <td><input size=\"25\" value=\"\" autocomplete=\"off\" id=\"password\" type=\"password\"/>
                <span class='form-description'>";
            // line 83
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_IfYouWouldLikeToChangeThePasswordTypeANewOne")), "html", null, true);
            echo "</span>
                <br/><br/><input size=\"25\" value=\"\" autocomplete=\"off\" id=\"passwordBis\" type=\"password\"/>
                <span class='form-description'> ";
            // line 85
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_TypeYourPasswordAgain")), "html", null, true);
            echo "</span>
            </td>
        </tr>
    ";
        }
        // line 89
        echo "</table>
";
        // line 90
        if (( !array_key_exists("isValidHost", $context) ||  !$this->getContext($context, "isValidHost"))) {
            // line 91
            echo "    <div id=\"injectedHostCannotChangePwd\">
        ";
            // line 92
            ob_start();
            // line 93
            echo "        ";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_InjectedHostCannotChangePwd", $this->getContext($context, "invalidHost"))), "html", null, true);
            echo "
        &nbsp;";
            // line 94
            if ( !$this->getContext($context, "isSuperUser")) {
                echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_EmailYourAdministrator", $this->getContext($context, "invalidHostMailLinkStart"), "</a>"));
            }
            // line 95
            echo "        ";
            $context["injectedHostCannotChangePwd"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
            // line 96
            echo "        ";
            echo call_user_func_array($this->env->getFilter('notification')->getCallable(), array($this->getContext($context, "injectedHostCannotChangePwd"), array("raw" => true, "context" => "error", "placeat" => "#injectedHostCannotChangePwd", "noclear" => true)));
            echo "
    </div>
    <br/>
";
        }
        // line 100
        echo "
";
        // line 101
        $context["ajax"] = $this->env->loadTemplate("ajaxMacros.twig");
        // line 102
        echo $context["ajax"]->geterrorDiv("ajaxErrorUserSettings");
        echo "
";
        // line 103
        echo $context["ajax"]->getloadingDiv("ajaxLoadingUserSettings");
        echo "
<input type=\"submit\" value=\"";
        // line 104
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Save")), "html", null, true);
        echo "\" id=\"userSettingsSubmit\" class=\"submit\"/>

<br/><br/>

<h2 id=\"excludeCookie\">";
        // line 108
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_ExcludeVisitsViaCookie")), "html", null, true);
        echo "</h2>
<p>
";
        // line 110
        if ($this->getContext($context, "ignoreCookieSet")) {
            // line 111
            echo "   ";
            echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_YourVisitsAreIgnoredOnDomain", "<strong>", $this->getContext($context, "piwikHost"), "</strong>"));
            echo "
";
        } else {
            // line 113
            echo "   ";
            echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_YourVisitsAreNotIgnored", "<strong>", "</strong>"));
            echo "
";
        }
        // line 115
        echo "</p>
<span style=\"margin-left:20px;\">
<a href='";
        // line 117
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('linkTo')->getCallable(), array(array("ignoreSalt" => $this->getContext($context, "ignoreSalt"), "action" => "setIgnoreCookie"))), "html", null, true);
        echo "#excludeCookie'>&rsaquo; ";
        if ($this->getContext($context, "ignoreCookieSet")) {
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_ClickHereToDeleteTheCookie")), "html", null, true);
            echo "
    ";
        } else {
            // line 118
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_ClickHereToSetTheCookieOnDomain", $this->getContext($context, "piwikHost"))), "html", null, true);
        }
        // line 119
        echo "    <br/>
</a></span>

<br/><br/>
";
    }

    public function getTemplateName()
    {
        return "@UsersManager/userSettings.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  354 => 119,  351 => 118,  343 => 117,  339 => 115,  333 => 113,  327 => 111,  325 => 110,  320 => 108,  313 => 104,  309 => 103,  305 => 102,  303 => 101,  300 => 100,  292 => 96,  289 => 95,  285 => 94,  280 => 93,  278 => 92,  275 => 91,  273 => 90,  270 => 89,  263 => 85,  258 => 83,  253 => 81,  250 => 80,  248 => 79,  242 => 75,  223 => 72,  212 => 71,  195 => 70,  189 => 67,  177 => 58,  173 => 57,  166 => 53,  160 => 52,  155 => 50,  149 => 49,  142 => 45,  134 => 39,  121 => 37,  114 => 36,  110 => 35,  106 => 34,  99 => 30,  93 => 27,  89 => 26,  83 => 23,  79 => 22,  71 => 17,  67 => 16,  62 => 14,  54 => 9,  50 => 8,  46 => 7,  39 => 4,  36 => 3,  11 => 1,);
    }
}
