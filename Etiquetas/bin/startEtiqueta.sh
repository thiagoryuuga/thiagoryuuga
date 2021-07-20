cd /sisetiq/bin
java -cp .:/sisetiq/lib/charva.jar:/sisetiq/lib/log4j-1.2rc1.jar:/sisetiq/lib/sfc.jar:/sisetiq/lib/ojdbc14.jar -Dcharva.color=1 -Djava.library.path=../lib/ br.com.esmaltec.etiqueta.view.ImpressaoEtiquetaTexto 200 31 LNX
