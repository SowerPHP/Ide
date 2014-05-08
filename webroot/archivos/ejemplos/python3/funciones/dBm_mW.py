#!/usr/bin/python3
# -*- coding: utf8 -*-

# importar biblioteca de matemáticas
import math

# cálculo de dBm a partir de una potencia en mW
def dBm(p) :
    return 10*math.log(p,10)

# cálculo de mW a partir de una potencia en dBm
# para p negativa -X0 = 10^(-X)
def mW(p) :
    return 10**(p/10)

# ejecutar funciones
print ('100 [mW] son', dBm(100), '[dBm]')
print ('20 [dBm] son', mW(20), '[mW]')
