{"version":3,"sources":["helper.js"],"names":["BX","namespace","Helper","frameOpenUrl","frameNode","openBtn","popupLoader","langId","ajaxUrl","currentStepId","notifyBlock","notifyNum","notifyText","notifyId","notifyButton","isAdmin","version","init","params","this","helpBtn","notifyData","runtimeUrl","notifyUrl","helpUrl","addEventListener","isOpen","close","show","setBlueHeroView","bind","window","proxy","event","origin","indexOf","data","action","setNotification","num","showNotification","contentWindow","postMessage","BXIM","openMessenger","user_id","getClass","Bitrix24","LeftMenuClass","getStructureForHelper","structure","menu","newArticleInfo","articleCount","lastTimestampCheckNewArticle","counter_update_date","needCheckNotify","checkNotification","additionalParam","url","type","isNotEmptyString","getFrame","src","SidePanel","Instance","open","getSliderId","contentCallback","slider","promise","Promise","fulfill","getContent","width","cacheable","events","onCloseComplete","onLoad","showFrame","onClose","addClass","getSlider","removeClass","classList","remove","content","create","attrs","className","children","getLoader","setTimeout","add","text","message","ajax","post","sessid","bitrix_sessid","isNaN","parseFloat","isFinite","numBlock","innerHTML","showFlyingHero","method","dataType","onsuccess","res","load","eval","time","id","body","button","onfailure","showAnimatedHero","browser","IsIE8","block","admin","panel","style","top","DIV","offsetHeight","document","appendChild","stage","swiffy","Stage","swiffyobject","setBackground","start","display"],"mappings":"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","file":""}