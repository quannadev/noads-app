package models

type Model struct {
	Name      string             `json:"name"`
	Link      string             `json:"link"`
	Thumbnail *string            `json:"thumbnail"`
	Episode   *map[string]string `json:"episodes"`
	Media     []string           `json:"media"`
}
