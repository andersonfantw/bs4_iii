<?php

/* admin.twig */
class __TwigTemplate_88a2ea5cc71a52815361aeff84fedbb30ec626623e74072534f40426464208ff extends Twig_Template
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
            echo "Piwik &rsaquo;";
        }
        echo " ";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_Administration")), "html", null, true);
    }

    // line 7
    public function block_body($context, array $blocks = array())
    {
        // line 8
        echo "    ";
        if ($this->getContext($context, "isSuperUser")) {
            // line 9
            echo "        ";
            $context["topMenuModule"] = "CoreAdminHome";
            // line 10
            echo "        ";
            $context["topMenuAction"] = "generalSettings";
            // line 11
            echo "    ";
        } else {
            // line 12
            echo "        ";
            $context["topMenuModule"] = "SitesManager";
            // line 13
            echo "        ";
            $context["topMenuAction"] = "index";
            // line 14
            echo "    ";
        }
        // line 15
        echo "    ";
        $this->displayParentBlock("body", $context, $blocks);
        echo "
";
    }

    // line 18
    public function block_root($context, array $blocks = array())
    {
        // line 19
        echo "    ";
        $this->env->loadTemplate("@CoreHome/_topScreen.twig")->display($context);
        // line 20
        echo "
    ";
        // line 21
        $context["ajax"] = $this->env->loadTemplate("ajaxMacros.twig");
        // line 22
        echo "    ";
        echo $context["ajax"]->getrequestErrorDiv(((array_key_exists("emailSuperUser", $context)) ? (_twig_default_filter($this->getContext($context, "emailSuperUser"), "")) : ("")));
        echo "
    ";
        // line 23
        echo call_user_func_array($this->env->getFunction('postEvent')->getCallable(), array("Template.beforeContent", "admin", $this->getContext($context, "currentModule")));
        echo "

    <div id=\"container\">

        ";
        // line 27
        if (( !array_key_exists("showMenu", $context) || $this->getContext($context, "showMenu"))) {
            // line 28
            echo "            ";
            $this->env->loadTemplate("@CoreAdminHome/_menu.twig")->display($context);
            // line 29
            echo "        ";
        }
        // line 30
        echo "
        <div id=\"content\" class=\"admin\">

            ";
        // line 33
        $this->env->loadTemplate("@CoreHome/_headerMessage.twig")->display($context);
        // line 34
        echo "            ";
        $this->env->loadTemplate("@CoreHome/_notifications.twig")->display($context);
        // line 35
        echo "
            <div class=\"ui-confirm\" id=\"alert\">
                <h2></h2>
                <input role=\"no\" type=\"button\" value=\"";
        // line 38
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Ok")), "html", null, true);
        echo "\"/>
            </div>

            ";
        // line 41
        $this->env->loadTemplate("@CoreHome/_warningInvalidHost.twig")->display($context);
        // line 42
        echo "
            ";
        // line 43
        $this->displayBlock('content', $context, $blocks);
        // line 45
        echo "
        </div>
    </div>
";
    }

    // line 43
    public function block_content($context, array $blocks = array())
    {
        // line 44
        echo "            ";
    }

    public function getTemplateName()
    {
        return "admin.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  152 => 44,  149 => 43,  142 => 45,  140 => 43,  137 => 42,  135 => 41,  129 => 38,  124 => 35,  121 => 34,  119 => 33,  114 => 30,  111 => 29,  108 => 28,  106 => 27,  99 => 23,  94 => 22,  92 => 21,  89 => 20,  86 => 19,  83 => 18,  76 => 15,  73 => 14,  70 => 13,  67 => 12,  64 => 11,  61 => 10,  58 => 9,  55 => 8,  52 => 7,  42 => 3,  38 => 1,  36 => 5,  11 => 1,);
    }
}
