## Elementos do Grupo
* Jorge Ferreira - up201207133
* Pedro Lima - up201403381
* Nuno Silva - up201404380

## Alguns Utilizadores da base de dados

```
#!md

ID: Ramiro
Pass: ramiro123
Restaurants que possui: Ramirinho
```



```
#!md

ID: Manuel
Pass: manuel123

```


```
#!md

ID: Francisco
Pass: francisco123

```


```
#!md

ID: Bino
Pass: facil
Restaurants que possui: Os Frangos
```

```
#!md

ID: arkit
Pass: 123
```


```
#!md

ID: darkSidersII
Pass: 123
```


## Biblioteca de Imagens

Para mostrar as imagens do restaurante, bem como os restaurantes no home do site foi utilizado o jssor que é um slider de imagens grátis.

Localizações onde o jssor foi utilizado:

 * index.php
 * restaurant.php


## Alguns aspetos

Para funcionar na sua plenitude, e como não era possível fazer de outra forma, é necessário alterar em registration.php na linha 67, o caminho do site.

Exemplo: 

https://paginas.fe.up.pt/~up201207133/trabalho/verify_email.php?email=' . $email . '&code=' . $code . '

para

https://paginas.fe.up.pt/~NUMERO/PASTA_DO_TRABALHO/verify_email.php?email=' . $email . '&code=' . $code . '