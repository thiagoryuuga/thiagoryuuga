package br.com.esmaltec.etiqueta.business;

import java.io.InputStream;
import java.util.Properties;
/**
 * @author Rafael Lima
 * Classe responsavel por buscar as configurações do 
 * sistem a partir do arquivo de configuração
 * *.properties
 */
public abstract class PropertyLoader
{

    public static Properties loadProperties(String name, ClassLoader loader)
    {
        Properties result;
        if(name == null)
            throw new IllegalArgumentException("null input: name");
        if(name.startsWith("/"))
            name = name.substring(1);
        if(name.endsWith(".properties"))
            name = name.substring(0, name.length() - ".properties".length());
        result = null;
        InputStream in = null;
        try
        {
            if(loader == null)
                loader = ClassLoader.getSystemClassLoader();
            name = name.replace('.', '/');
            if(!name.endsWith(".properties"))
                name = name.concat(".properties");
            in = loader.getResourceAsStream(name);
            if(in != null)
            {
                result = new Properties();
                result.load(in);
            }
        }
        catch(Exception e)
        {
            result = null;
        }
        finally
        {
            if(in != null)
                try
                {
                    in.close();
                }
                catch(Throwable throwable) { }
        }
        if(result == null)
            throw new IllegalArgumentException("could not load [" + name + "]" + " as " + "a classloader resource");
        else
            return result;
    }

    public static Properties loadProperties(String name)
    {
        return loadProperties(name, Thread.currentThread().getContextClassLoader());
    }

    private PropertyLoader()
    {
    }
    
}