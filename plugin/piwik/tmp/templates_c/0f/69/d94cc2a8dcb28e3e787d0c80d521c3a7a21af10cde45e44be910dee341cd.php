<?php

/* user.twig */
class __TwigTemplate_0f69d94cc2a8dcb28e3e787d0c80d521c3a7a21af10cde45e44be910dee341cd extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        try {
            $this->parent = $this->env->loadTemplate("layout.twig");
        } catch (Twig_Error_Loader $e) {
            $e->setTemplateFile($this->getTemplateName());
            $e->setTemplateLine(1);

            throw $e;
        }

        $this->blocks = array(
            'pageTitle' => array($this, 'block_pageTitle'),
            'body' => array($this, 'block_body'),
            'root' => array($this, 'block_root'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 5
        $context["bodyClass"] = call_user_func_array($this->env->getFunction('postEvent')->getCallable(), array("Template.bodyClass", "admin"));
        // line 1
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_pageTitle($context, array $blocks = array())
    {
        if ( !$this->getContext($context, "isCustomLogo")) {
            echo "Piwik &rsaquo; ";
        }
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_Administration")), "html", null, true);
    }

    // line 7
    public function block_body($context, array $blocks = array())
    {
        // line 8
        echo "    ";
        if ($this->getContext($context, "userIsAnonymous")) {
            // line 9
            echo "        ";
            $context["topMenuModule"] = "Feedback";
            // line 10
            echo "        ";
            $context["topMenuAction"] = "index";
            // line 11
            echo "    ";
        } else {
            // line 12
            echo "        ";
            if (($this->getContext($context, "currentModule") != "Feedback")) {
                // line 13
                echo "            ";
                $context["topMenuModule"] = "UsersManager";
                // line 14
                echo "            ";
                $context["topMenuAction"] = "userSettings";
                // line 15
                echo "        ";
            }
            // line 16
            echo "    ";
        }
        // line 17
        echo "    ";
        $this->displayParentBlock("body", $context, $blocks);
        echo "
";
    }

    // line 20
    public function block_root($context, array $blocks = array())
    {
        // line 21
        echo "    ";
        $this->env->loadTemplate("@CoreHome/_topScreen.twig")->display($context);
        // line 22
        echo "
    ";
        // line 23
        $context["ajax"] = $this->env->loadTemplate("ajaxMacros.twig");
        // line 24
        echo "    ";
        echo $context["ajax"]->getrequestErrorDiv(((array_key_exists("emailSuperUser", $context)) ? (_twig_default_filter($this->getContext($context, "emailSuperUser"), "")) : ("")));
        echo "
    ";
        // line 25
        echo call_user_func_array($this->env->getFunction('postEvent')->getCallable(), array("Template.beforeContent", "user", $this->getContext($context, "currentModule")));
        echo "

    <div id=\"container\">

        ";
        // line 29
        if (( !array_key_exists("showMenu", $context) || $this->getContext($context, "showMenu"))) {
            // line 30
            echo "            ";
            $this->env->loadTemplate("@CoreHome/_userMenu.twig")->display($context);
            // line 31
            echo "        ";
        }
        // line 32
        echo "
        <div id=\"content\" class=\"admin user\">

            ";
        // line 35
        $this->env->loadTemplate("@CoreHome/_notifications.twig")->display($context);
        // line 36
        echo "
            <div class=\"ui-confirm\" id=\"alert\">
                <h2></h2>
                <input role=\"no\" type=\"button\" value=\"";
        // line 39
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Ok")), "html", null, true);
        echo "\"/>
            </div>

            ";
        // line 42
        $this->displayBlock('content', $context, $blocks);
        // line 44
        echo "
        </div>
    </div>
";
    }

    // line 42
    public function block_content($context, array $blocks = array())
    {
        // line 43
        echo "            ";
    }

    public function getTemplateName()
    {
        return "user.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  149 => 43,  146 => 42,  139 => 44,  137 => 42,  131 => 39,  126 => 36,  124 => 35,  119 => 32,  116 => 31,  113 => 30,  111 => 29,  104 => 25,  99 => 24,  97 => 23,  94 => 22,  91 => 21,  88 => 20,  81 => 17,  78 => 16,  75 => 15,  72 => 14,  69 => 13,  66 => 12,  63 => 11,  60 => 10,  57 => 9,  54 => 8,  51 => 7,  42 => 3,  38 => 1,  36 => 5,  11 => 1,);
    }
}
