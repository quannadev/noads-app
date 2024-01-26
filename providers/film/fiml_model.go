package film

type Model struct {
	Link    string             `json:"link"`
	Episode *map[string]string `json:"episodes"`
	Media   []string           `json:"media"`
}
