FROM nginx:alpine

RUN apk update && apk add tzdata nano bash

# Définition du fuseau horaire
ENV TZ=Europe/Paris

# Copie du fichier de fuseau horaire
RUN cp /usr/share/zoneinfo/Europe/Paris /etc/localtime \
    && echo "Europe/Paris" > /etc/timezone

WORKDIR /var/www/
