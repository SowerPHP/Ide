#!/usr/bin/python
# -*- coding: utf8 -*-

# importar biblioteca del sistema
import sys

# verificar parámetros pasados al programa
if len(sys.argv) != 3 :
    sys.stderr.write("Se deben pasar dos numeros como argumentos\n")
    sys.exit(1)

# números
a = int(sys.argv[1])
b = int(sys.argv[2])

# obtener el mayor utilizando una función
def maximo (n1, n2) :
    if n1 > n2 :
        mayor = n1
    else :
        mayor = n2
    return mayor

# ejecutar función
print (maximo(a, b))
