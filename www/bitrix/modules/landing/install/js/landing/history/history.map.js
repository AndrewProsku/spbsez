{"version":3,"sources":["history.js"],"names":["BX","namespace","UNDO","REDO","INIT","RESOLVED","PENDING","MAX_ENTRIES_COUNT","isPlainObject","Landing","Utils","bind","fireCustomEvent","History","this","stack","commands","position","state","commandState","onStorage","window","registerBaseCommands","then","load","save","onInit","instance","getInstance","top","history","registerCommand","Command","id","undo","Action","editText","redo","editImage","editIcon","editLink","sortBlock","removeBlock","addBlock","editStyle","removeCard","addCard","Promise","resolve","asyncParse","str","worker","Worker","postMessage","addEventListener","event","data","asyncStringify","obj","currentPageId","Main","err","localStorage","historyData","reject","landingData","Object","keys","forEach","key","index","push","Entry","shift","Math","min","parseInt","length","catch","all","allString","clear","removePageHistory","pageId","offset","entry","command","onUpdate","onActualize","onNewBranch","removeEntities","entities","fetchEntities","items","blocks","images","item","block","prototype","canUndo","canRedo","startIndex","deleteCount","deletedEntries","splice","entries"],"mappings":"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","file":""}