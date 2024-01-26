package main

import (
	"log"
	app2 "noads/app"
)

func main() {
	app := app2.NewApplication()
	log.Fatal(app.Run())
}
