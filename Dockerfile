FROM golang:1.21-alpine as builder

WORKDIR /app

COPY go.mod ./go.sum ./

RUN go mod download

COPY . .

RUN CGO_ENABLED=0 go build -o noads_app ./main.go

FROM alpine:latest

RUN apk --no-cache add ca-certificates \
    && rm -rf /var/cache/*


WORKDIR /app

COPY --from=builder /app/noads_app .

EXPOSE 8080

CMD ["/app/noads_app"]
