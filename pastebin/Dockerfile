FROM alpine:latest
RUN cd /usr/local/bin && \
TAG=$(wget -q -O- https://api.github.com/repos/w4/bin/releases/latest | grep tag_name | cut -d"\"" -f4) && \
wget -qO - https://github.com/w4/bin/releases/download/$TAG/bin-$TAG-x86_64-unknown-linux-musl.tar.gz | tar xvz
RUN adduser --system --uid 8000 --disabled-password --no-create-home binuser
USER binuser
EXPOSE 8000
ENTRYPOINT ["/usr/local/bin/bin"]
