package film

import "testing"

func TestOPhimGetLink(t *testing.T) {
	provider := NewOPhim()
	query := "cô đi mà lấy chồng tôi"
	data, err := provider.GetLink(query)
	if err != nil {
		t.Error(err)
	}
	t.Logf("%+v", data)
}
