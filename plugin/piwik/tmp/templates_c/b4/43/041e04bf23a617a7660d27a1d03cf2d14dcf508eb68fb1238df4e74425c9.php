<?php

/* @Login/login.twig */
class __TwigTemplate_b443041e04bf23a617a7660d27a1d03cf2d14dcf508eb68fb1238df4e74425c9 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        try {
            $this->parent = $this->env->loadTemplate("@Morpheus/layout.twig");
        } catch (Twig_Error_Loader $e) {
            $e->setTemplateFile($this->getTemplateName());
            $e->setTemplateLine(1);

            throw $e;
        }

        $this->blocks = array(
            'head' => array($this, 'block_head'),
            'pageTitle' => array($this, 'block_pageTitle'),
            'pageDescription' => array($this, 'block_pageDescription'),
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "@Morpheus/layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 24
        $context["bodyId"] = "loginPage";
        // line 1
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_head($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        $this->displayParentBlock("head", $context, $blocks);
        echo "
    <script type=\"text/javascript\" src=\"libs/bower_components/jquery-placeholder/jquery.placeholder.js\"></script>
    <!--[if lt IE 9]>
    <script src=\"libs/bower_components/html5shiv/dist/html5shiv.min.js\"></script>
    <![endif]-->
    <script type=\"text/javascript\" src=\"libs/jquery/jquery.smartbanner.js\"></script>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"libs/jquery/stylesheets/jquery.smartbanner.css\" />

    <script type=\"text/javascript\">
        \$(function () {
            \$('#form_login').focus();
            \$('input').placeholder();
            \$.smartbanner({title: \"Piwik Mobile 2\", author: \"Piwik team\", hideOnInstall: false, layer: true, icon: \"plugins/CoreHome/images/googleplay.png\"});
        });
    </script>
";
    }

    // line 21
    public function block_pageTitle($context, array $blocks = array())
    {
        if (($this->getContext($context, "isCustomLogo") == false)) {
            echo "Piwik &rsaquo; ";
        }
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LogIn")), "html", null, true);
    }

    // line 22
    public function block_pageDescription($context, array $blocks = array())
    {
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_OpenSourceWebAnalytics")), "html", null, true);
    }

    // line 26
    public function block_body($context, array $blocks = array())
    {
        // line 27
        echo "
    ";
        // line 28
        echo call_user_func_array($this->env->getFunction('postEvent')->getCallable(), array("Template.beforeTopBar", "login"));
        echo "
    ";
        // line 29
        echo call_user_func_array($this->env->getFunction('postEvent')->getCallable(), array("Template.beforeContent", "login"));
        echo "

    ";
        // line 31
        $this->env->loadTemplate("_iframeBuster.twig")->display($context);
        // line 32
        echo "
    <div id=\"notificationContainer\">
    </div>

    <div id=\"logo\">
        ";
        // line 37
        if (($this->getContext($context, "isCustomLogo") == false)) {
            // line 38
            echo "            <a href=\"http://piwik.org\" title=\"";
            echo twig_escape_filter($this->env, $this->getContext($context, "linkTitle"), "html", null, true);
            echo "\">
        ";
        }
        // line 40
        echo "        ";
        if ($this->getContext($context, "hasSVGLogo")) {
            // line 41
            echo "            <img src='";
            echo twig_escape_filter($this->env, $this->getContext($context, "logoSVG"), "html", null, true);
            echo "' title=\"";
            echo twig_escape_filter($this->env, $this->getContext($context, "linkTitle"), "html", null, true);
            echo "\" alt=\"Piwik\" class=\"ie-hide\"/>
            <!--[if lt IE 9]>
        ";
        }
        // line 44
        echo "        <img src='";
        echo twig_escape_filter($this->env, $this->getContext($context, "logoLarge"), "html", null, true);
        echo "' title=\"";
        echo twig_escape_filter($this->env, $this->getContext($context, "linkTitle"), "html", null, true);
        echo "\" alt=\"Piwik\" />
        ";
        // line 45
        if ($this->getContext($context, "hasSVGLogo")) {
            echo "<![endif]-->";
        }
        // line 46
        echo "
        ";
        // line 47
        if ($this->getContext($context, "isCustomLogo")) {
            // line 48
            echo "            ";
            ob_start();
            // line 49
            echo "            <i><a href=\"http://piwik.org/\" rel=\"noreferrer\"  target=\"_blank\">";
            echo twig_escape_filter($this->env, $this->getContext($context, "linkTitle"), "html", null, true);
            echo "</a></i>
            ";
            $context["poweredByPiwik"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
            // line 51
            echo "        ";
        }
        // line 52
        echo "
        ";
        // line 53
        if (($this->getContext($context, "isCustomLogo") == false)) {
            // line 54
            echo "            </a>
            <div class=\"description\">
                <a href=\"http://piwik.org\" title=\"";
            // line 56
            echo twig_escape_filter($this->env, $this->getContext($context, "linkTitle"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getContext($context, "linkTitle"), "html", null, true);
            echo "</a>
                <div class=\"arrow\"></div>
            </div>
        ";
        }
        // line 60
        echo "    </div>

    <section class=\"loginSection\">

        ";
        // line 65
        echo "        ";
        if (((array_key_exists("isValidHost", $context) && array_key_exists("invalidHostMessage", $context)) && ($this->getContext($context, "isValidHost") == false))) {
            // line 66
            echo "            ";
            $this->env->loadTemplate("@CoreHome/_warningInvalidHost.twig")->display($context);
            // line 67
            echo "        ";
        } else {
            // line 68
            echo "            <div id=\"message_container\">

                ";
            // line 70
            echo twig_include($this->env, $context, "@Login/_formErrors.twig", array("formErrors" => $this->getAttribute($this->getContext($context, "form_data"), "errors", array())));
            echo "

                ";
            // line 72
            if ($this->getContext($context, "AccessErrorString")) {
                // line 73
                echo "                    <div class=\"message_error\">
                        <strong>";
                // line 74
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Error")), "html", null, true);
                echo "</strong>: ";
                echo $this->getContext($context, "AccessErrorString");
                echo "<br/>
                    </div>
                ";
            }
            // line 77
            echo "
                ";
            // line 78
            if ($this->getContext($context, "infoMessage")) {
                // line 79
                echo "                    <p class=\"message\">";
                echo $this->getContext($context, "infoMessage");
                echo "</p>
                ";
            }
            // line 81
            echo "            </div>
            <form ";
            // line 82
            echo $this->getAttribute($this->getContext($context, "form_data"), "attributes", array());
            echo " ng-non-bindable>
                <h1>";
            // line 83
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LogIn")), "html", null, true);
            echo "</h1>
                <fieldset class=\"inputs\">
                    <input type=\"text\" name=\"form_login\" id=\"login_form_login\" class=\"input\" value=\"\" size=\"20\"
                           tabindex=\"10\"
                           placeholder=\"";
            // line 87
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Username")), "html", null, true);
            echo "\" autofocus=\"autofocus\"/>
                    <input type=\"password\" name=\"form_password\" id=\"login_form_password\" class=\"input\" value=\"\" size=\"20\"
                           tabindex=\"20\"
                           placeholder=\"";
            // line 90
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Password")), "html", null, true);
            echo "\"/>
                    <input type=\"hidden\" name=\"form_nonce\" id=\"login_form_nonce\" value=\"";
            // line 91
            echo twig_escape_filter($this->env, $this->getContext($context, "nonce"), "html", null, true);
            echo "\"/>
                </fieldset>

                <fieldset class=\"actions\">
                    <input name=\"form_rememberme\" type=\"checkbox\" id=\"login_form_rememberme\" value=\"1\" tabindex=\"90\"
                           ";
            // line 96
            if ($this->getAttribute($this->getAttribute($this->getContext($context, "form_data"), "form_rememberme", array()), "value", array())) {
                echo "checked=\"checked\" ";
            }
            echo "/>
                    <label for=\"login_form_rememberme\">";
            // line 97
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_RememberMe")), "html", null, true);
            echo "</label>
                    <input class=\"submit\" id='login_form_submit' type=\"submit\" value=\"";
            // line 98
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LogIn")), "html", null, true);
            echo "\"
                           tabindex=\"100\"/>
                </fieldset>
            </form>
            <form id=\"reset_form\" style=\"display:none;\" method=\"post\" ng-non-bindable>
                <fieldset class=\"inputs\">
                    <input type=\"text\" name=\"form_login\" id=\"reset_form_login\" class=\"input\" value=\"\" size=\"20\"
                           tabindex=\"10\"
                           placeholder=\"";
            // line 106
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LoginOrEmail")), "html", null, true);
            echo "\"/>
                    <input type=\"hidden\" name=\"form_nonce\" id=\"reset_form_nonce\" value=\"";
            // line 107
            echo twig_escape_filter($this->env, $this->getContext($context, "nonce"), "html", null, true);
            echo "\"/>

                    <input type=\"password\" name=\"form_password\" id=\"reset_form_password\" class=\"input\" value=\"\" size=\"20\"
                           tabindex=\"20\" autocomplete=\"off\"
                           placeholder=\"";
            // line 111
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Password")), "html", null, true);
            echo "\"/>

                    <input type=\"password\" name=\"form_password_bis\" id=\"reset_form_password_bis\" class=\"input\" value=\"\"
                           size=\"20\" tabindex=\"30\" autocomplete=\"off\"
                           placeholder=\"";
            // line 115
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_PasswordRepeat")), "html", null, true);
            echo "\"/>
                </fieldset>

                <fieldset class=\"actions\">
                    <span class=\"loadingPiwik\" style=\"display:none;\">
                        <img alt=\"Loading\" src=\"plugins/Morpheus/images/loading-blue.gif\"/>
                    </span>
                    <input class=\"submit\" id='reset_form_submit' type=\"submit\"
                           value=\"";
            // line 123
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_ChangePassword")), "html", null, true);
            echo "\" tabindex=\"100\"/>
                </fieldset>

                <input type=\"hidden\" name=\"module\" value=\"";
            // line 126
            echo twig_escape_filter($this->env, $this->getContext($context, "loginModule"), "html", null, true);
            echo "\"/>
                <input type=\"hidden\" name=\"action\" value=\"resetPassword\"/>
            </form>
            <p id=\"nav\">
                ";
            // line 130
            echo call_user_func_array($this->env->getFunction('postEvent')->getCallable(), array("Template.loginNav", "top"));
            echo "
                <a id=\"login_form_nav\" href=\"#\"
                   title=\"";
            // line 132
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LostYourPassword")), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LostYourPassword")), "html", null, true);
            echo "</a>
                <a id=\"alternate_reset_nav\" href=\"#\" style=\"display:none;\"
                   title=\"";
            // line 134
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LogIn")), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LogIn")), "html", null, true);
            echo "</a>
                <a id=\"reset_form_nav\" href=\"#\" style=\"display:none;\"
                   title=\"";
            // line 136
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Mobile_NavigationBack")), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Cancel")), "html", null, true);
            echo "</a>
                ";
            // line 137
            echo call_user_func_array($this->env->getFunction('postEvent')->getCallable(), array("Template.loginNav", "bottom"));
            echo "
            </p>
            ";
            // line 139
            if (array_key_exists("poweredByPiwik", $context)) {
                // line 140
                echo "                <p id=\"piwik\">
                    ";
                // line 141
                echo twig_escape_filter($this->env, $this->getContext($context, "poweredByPiwik"), "html", null, true);
                echo "
                </p>
            ";
            }
            // line 144
            echo "            <div id=\"lost_password_instructions\" style=\"display:none;\">
                <p class=\"message\">";
            // line 145
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_ResetPasswordInstructions")), "html", null, true);
            echo "</p>
            </div>
        ";
        }
        // line 148
        echo "    </section>

";
    }

    public function getTemplateName()
    {
        return "@Login/login.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  364 => 148,  358 => 145,  355 => 144,  349 => 141,  346 => 140,  344 => 139,  339 => 137,  333 => 136,  326 => 134,  319 => 132,  314 => 130,  307 => 126,  301 => 123,  290 => 115,  283 => 111,  276 => 107,  272 => 106,  261 => 98,  257 => 97,  251 => 96,  243 => 91,  239 => 90,  233 => 87,  226 => 83,  222 => 82,  219 => 81,  213 => 79,  211 => 78,  208 => 77,  200 => 74,  197 => 73,  195 => 72,  190 => 70,  186 => 68,  183 => 67,  180 => 66,  177 => 65,  171 => 60,  162 => 56,  158 => 54,  156 => 53,  153 => 52,  150 => 51,  144 => 49,  141 => 48,  139 => 47,  136 => 46,  132 => 45,  125 => 44,  116 => 41,  113 => 40,  107 => 38,  105 => 37,  98 => 32,  96 => 31,  91 => 29,  87 => 28,  84 => 27,  81 => 26,  75 => 22,  66 => 21,  45 => 4,  42 => 3,  38 => 1,  36 => 24,  11 => 1,);
    }
}
