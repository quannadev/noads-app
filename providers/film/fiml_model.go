package film

type Model struct {
	Name    string             `json:"name"`
	Link    string             `json:"link"`
	Episode *map[string]string `json:"episodes"`
	Media   []string           `json:"media"`
}
