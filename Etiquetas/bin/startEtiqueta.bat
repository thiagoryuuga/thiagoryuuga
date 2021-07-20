c:

copy /y C:\Developer\eclipse\workspace\EtiquetaProducao\bin\ c:\sisetiq\bin
copy /y C:\Developer\eclipse\workspace\EtiquetaProducao\xml\ c:\sisetiq\xml
copy /y C:\Developer\eclipse\workspace\EtiquetaProducao\bin\config c:\sisetiq\bin\config
copy /y C:\Developer\eclipse\workspace\EtiquetaProducao\bin\br\com\esmaltec\etiqueta\business c:\sisetiq\bin\br\com\esmaltec\etiqueta\business
copy /y C:\Developer\eclipse\workspace\EtiquetaProducao\bin\br\com\esmaltec\etiqueta\view c:\sisetiq\bin\br\com\esmaltec\etiqueta\view

cd c:\sisetiq\bin
java -cp .;c:\sisetiq\lib\charva.jar;c:\sisetiq\lib\log4j-1.2rc1.jar;c:\sisetiq\lib\sfc.jar;c:\sisetiq\lib\ojdbc14.jar -Dcharva.color=1 br.com.esmaltec.etiqueta.view.ImpressaoEtiquetaTexto 200 106 WIN