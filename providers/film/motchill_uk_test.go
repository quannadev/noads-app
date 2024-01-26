package film

import "testing"

func TestCheckLink(t *testing.T) {
	motChillUk := NewMotChillUk()
	if !motChillUk.CheckLink("https://motchill.uk/xem-phim/cua-hang-sat-thu/tap-3-170299") {
		t.Error("link is invalid")
	}
}

func TestGetLink(t *testing.T) {
	motChillUk := NewMotChillUk()
	data, err := motChillUk.GetLink("https://motchill.uk/xem-phim/cua-hang-sat-thu/tap-3-170299")
	if err != nil {
		t.Error(err)
	}
	if data == nil {
		t.Error("data is nil")
	}
	t.Log(data)
}

func TestGetName(t *testing.T) {
	motChillUk := NewMotChillUk()
	data, err := motChillUk.GetLink("https://motchill.uk/xem-phim/cua-hang-sat-thu/tap-3-170299")
	if err != nil {
		t.Error(err)
	}
	if data == nil {
		t.Error("data is nil")
	}
	name := data.(Model).Name
	if name == "" {
		t.Error("name is empty")
	}
	t.Log(name)
}
